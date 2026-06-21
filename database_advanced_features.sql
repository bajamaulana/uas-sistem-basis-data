DELIMITER //

-- ==========================================
-- 1. TRIGGERS (Min 3)
-- ==========================================

-- Trigger 1: Audit Log for Orders table
-- Logs changes when an order status is updated
DROP TRIGGER IF EXISTS trg_audit_orders_update//
CREATE TRIGGER trg_audit_orders_update
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF OLD.status != NEW.status THEN
        INSERT INTO audit_logs (table_name, record_id, action, old_value, new_value, changed_by)
        VALUES ('orders', NEW.id, 'UPDATE', CONCAT('status:', OLD.status), CONCAT('status:', NEW.status), NEW.employee_id);
    END IF;
END//

-- Trigger 2: Validate Stock Before Out Transaction
-- Prevents inventory transactions that result in negative stock
DROP TRIGGER IF EXISTS trg_validate_stock_before_out//
CREATE TRIGGER trg_validate_stock_before_out
BEFORE INSERT ON inventory_transactions
FOR EACH ROW
BEGIN
    DECLARE current_stock DECIMAL(10,2);
    
    IF NEW.transaction_type = 'Out' THEN
        SELECT stock_quantity INTO current_stock FROM ingredients WHERE id = NEW.ingredient_id;
        IF current_stock < NEW.quantity THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error: Insufficient stock for transaction!';
        END IF;
    END IF;
END//

-- Trigger 3: Update Stock After Transaction
-- Automatically adjusts ingredient stock after an inventory transaction
DROP TRIGGER IF EXISTS trg_update_stock_after_trx//
CREATE TRIGGER trg_update_stock_after_trx
AFTER INSERT ON inventory_transactions
FOR EACH ROW
BEGIN
    IF NEW.transaction_type = 'In' THEN
        UPDATE ingredients SET stock_quantity = stock_quantity + NEW.quantity WHERE id = NEW.ingredient_id;
    ELSEIF NEW.transaction_type = 'Out' THEN
        UPDATE ingredients SET stock_quantity = stock_quantity - NEW.quantity WHERE id = NEW.ingredient_id;
    ELSEIF NEW.transaction_type = 'Adjustment' THEN
        UPDATE ingredients SET stock_quantity = NEW.quantity WHERE id = NEW.ingredient_id;
    END IF;
END//

-- ==========================================
-- 2. USER DEFINED FUNCTIONS (Min 2)
-- ==========================================

-- Function 1: Calculate Tax
-- Calculates standard 10% tax for a given amount
DROP FUNCTION IF EXISTS fn_calculate_tax//
CREATE FUNCTION fn_calculate_tax(amount DECIMAL(10,2))
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    DECLARE tax_amount DECIMAL(10,2);
    SET tax_amount = amount * 0.10;
    RETURN tax_amount;
END//

-- Function 2: Get Customer Loyalty Total
-- Returns the total revenue brought in by a specific customer
DROP FUNCTION IF EXISTS fn_get_customer_loyalty//
CREATE FUNCTION fn_get_customer_loyalty(cust_id INT)
RETURNS DECIMAL(10,2)
READS SQL DATA
BEGIN
    DECLARE total_spent DECIMAL(10,2);
    
    SELECT SUM(total_amount) INTO total_spent 
    FROM orders 
    WHERE customer_id = cust_id AND status = 'Completed';
    
    IF total_spent IS NULL THEN
        SET total_spent = 0.00;
    END IF;
    
    RETURN total_spent;
END//

-- ==========================================
-- 3. STORED PROCEDURES, TCL, AND CURSORS (Min 3 SPs, 1 Cursor, COMMIT/ROLLBACK)
-- ==========================================

-- Stored Procedure 1: Restock Ingredient with TCL
-- Safely inserts an 'In' transaction using COMMIT/ROLLBACK
DROP PROCEDURE IF EXISTS sp_restock_ingredient//
CREATE PROCEDURE sp_restock_ingredient(
    IN p_ingredient_id INT, 
    IN p_quantity DECIMAL(10,2), 
    IN p_remarks TEXT
)
BEGIN
    DECLARE exit handler for sqlexception
    BEGIN
        -- Error occurred, rollback the transaction
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;
    
    -- Insert transaction (Trigger will automatically update stock)
    INSERT INTO inventory_transactions (ingredient_id, transaction_type, quantity, remarks)
    VALUES (p_ingredient_id, 'In', p_quantity, p_remarks);
    
    COMMIT;
END//

-- Stored Procedure 2: Checkout Order with TCL
-- Updates order status to completed and applies logic safely
DROP PROCEDURE IF EXISTS sp_checkout_order//
CREATE PROCEDURE sp_checkout_order(IN p_order_id INT)
BEGIN
    DECLARE v_status ENUM('Pending','Processing','Completed','Cancelled');
    
    DECLARE exit handler for sqlexception
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;
    
    -- Lock the row for update to prevent concurrent checkout
    SELECT status INTO v_status FROM orders WHERE id = p_order_id FOR UPDATE;
    
    IF v_status != 'Completed' THEN
        UPDATE orders SET status = 'Completed' WHERE id = p_order_id;
        COMMIT;
    ELSE
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Order is already completed!';
    END IF;
END//

-- Stored Procedure 3: Generate Daily Revenue Report Using CURSOR
-- Iterates over all completed orders for a specific date to summarize revenue by payment method
DROP PROCEDURE IF EXISTS sp_generate_daily_report//
CREATE PROCEDURE sp_generate_daily_report(IN p_report_date DATE)
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_payment_id INT;
    DECLARE v_amount DECIMAL(10,2);
    
    -- Variables for accumulation
    DECLARE total_cash DECIMAL(10,2) DEFAULT 0.00;
    DECLARE total_card DECIMAL(10,2) DEFAULT 0.00;
    DECLARE total_qris DECIMAL(10,2) DEFAULT 0.00;
    
    -- Declare Cursor
    DECLARE order_cursor CURSOR FOR 
        SELECT payment_method_id, total_amount 
        FROM orders 
        WHERE DATE(order_date) = p_report_date AND status = 'Completed';
        
    -- Declare Continue Handler for Cursor
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN order_cursor;
    
    read_loop: LOOP
        FETCH order_cursor INTO v_payment_id, v_amount;
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        -- Categorize revenue based on payment method ID
        IF v_payment_id = 1 THEN
            SET total_cash = total_cash + v_amount;
        ELSEIF v_payment_id = 2 THEN
            SET total_card = total_card + v_amount;
        ELSE
            SET total_qris = total_qris + v_amount;
        END IF;
    END LOOP;
    
    CLOSE order_cursor;
    
    -- Return the accumulated result
    SELECT p_report_date AS ReportDate, 
           total_cash AS CashRevenue, 
           total_card AS CardRevenue, 
           total_qris AS QRISRevenue,
           (total_cash + total_card + total_qris) AS TotalRevenue;
END//

DELIMITER ;
