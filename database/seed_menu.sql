-- ==========================================================
-- Dummy Data for Ngopidea Artisanal Coffee (Menu Items)
-- Berdasarkan file menu.php di website
-- ==========================================================

-- 1. Insert Kategori Menu (categories)
INSERT INTO `categories` (`id`, `category_name`, `description`) VALUES
(1, 'Espresso Based', 'Classic coffee drinks based on rich espresso shots.'),
(2, 'Manual Brew', 'Hand-poured filter coffee highlighting single-origin beans.'),
(3, 'Signature Specials', 'Unique house creations crafted by our master baristas.'),
(4, 'Non-Coffee', 'Delicious alternatives for when you need a break from caffeine.');

-- 2. Insert Daftar Menu (products)
INSERT INTO `products` (`category_id`, `product_name`, `description`, `price`, `image_url`) VALUES
-- Espresso Based
(1, 'Flat White', 'Double shot espresso with silky micro-foam, balanced and creamy.', 5.50, 'https://lh3.googleusercontent.com/aida-public/AB6AXuAN9LdZdRwV2LLK1HePV6-vyoWS_C5B6517OpOEOp9dxsY4a7TSwyYeFt_At8QF69qyh5hYAU4MlArWsXTq9b53iXntocZv0OFlsAzchpQxDSYrnCcQ5nLN3iOW909w1w45aGSOcw8oqf4cPYuOtEBLwbBBldBmskbhM7chCYVSwPAoh3ExDrkyBrpqZtRguW5m4milrSiaTRtS-u7kzgBl9qn53CcoLyhhYy9wjXFOfZVY8bXCF7Xyg_JiYbWPUY74g3VdGfF7qpc'),
(1, 'Cafe Latte', 'Smooth espresso combined with steamed milk and a light layer of foam.', 5.25, 'https://lh3.googleusercontent.com/aida-public/AB6AXuCUJ165tdjWmB7xh8ldA9-oXrVWlJgaZdkQVV1MG7-kml0LlyhDC-WB0dIRSTvAm8bucDFZq8YiruOw1R_l6Q-3GFc_nzl7TNucu8beXxQ4tumfJj_eS9UfYBwHWH2-DD2BHvN-EOZmf0gN9epEZXcT6qqVbTZ9rcx1M8xeVNE3o_vL0MhyFJgcU4yT0ORlHNQb31c8YwPVkWOpkyQMbn67SWYCBkrcHKwxheWPGoVv13MKOIxLXZ_4rLWZ6FUGeR0LykjuytL4cP0'),
(1, 'Cappuccino', 'Classic proportions of espresso, steamed milk, and dense foam.', 5.25, 'https://lh3.googleusercontent.com/aida-public/AB6AXuCy2DNJxYJX2_td_9P47BlD8s0X-tFMueFcCrpCmZxvFkvFVIFcaSf8IuMbC5GQYcKWfX6vgKyz_OwuMHUb8CAo924rZ2BCpNRQ-Jf_H1HuGOHOhcLgiaKgldYpOSzl6rp4oG0uBPUyfgrPgK2RlEc5bnkAHqcdfXCJoShVH7QQvLKjctjPmx4xjUgGMwuCaEb8bI5nlan1mii4jkUoFwB4qc2ztfFMyhZ4TvQf1NWpIyowb__HYT5unshB15Ucv5JvlyfOToxDHjg'),
(1, 'Americano', 'Rich espresso shots topped with hot water for a bold, clean cup.', 4.50, 'https://lh3.googleusercontent.com/aida-public/AB6AXuAtD4nR3bOcM9Nd9oLFDcRlDifiux-ekPn3dNntos2pNtR8ycKcsO7dE2WrDmKwyhnMJ2VJvolcLgKy39gFBM4E79VAFNQbb4GYP_rqCxtc5hRxRLJxBwkUI1V5CI2srOT4RtJcnIALMQpNiiR2z3VQ8OVZTqWyOPxc3Icff5TIZ3rh_BNrLGvk887xrFpqg70-0Xl5ik_VtnhfPpYuiJZNHY5SbzJaX0s7B8mzLx30PCDuKXVX-dZJooNnwu2d_nL3_5zTF5v94PI'),

-- Manual Brew
(2, 'Artisanal V60', 'A clean and bright pour-over highlighting the unique terroir of our seasonal beans.', 6.50, 'https://lh3.googleusercontent.com/aida-public/AB6AXuBEUqKNb4CjJ6eFHhmtpymF634sv5SbbsbtGSyByobOK4uU6XXPgBKpnwYt8royrKzvR-jyScoYM3c137eGRHYnARsg86Xxw4GuolDrP1dcJ4X01R7A67PeHRtoYLPhBQMIVoTLdI0qYZdaQJkIEOdT47_DBVXGGLZ9JoCUBYjPfQH3IAhm8HvQoeyT4JTnQ0tZ1yXZPg4A7Ag9DKPK6VnSS1zBC9NC6peuTqNaqNpSCB-kH9knQoZLjHomGh8VU1gK8o2-mLADutU'),
(2, 'Chemex Brew', 'Heavier body with exceptional clarity, perfect for sharing or a long contemplative sip.', 6.75, 'https://lh3.googleusercontent.com/aida-public/AB6AXuDtSe1V4IjqEJn4pW1NK2w_vqxBs3wB7AGPKK8OevDsTYV0fxT3IjYhOOwECcL1r2K-qXQWezaitYKk0MPNaooa31623BSUoYosNhp_rEX7VU26qNj6kMVdxqYmKnOcKJQO861F-ZANESddHUin4jV7xFrYuzV-bUb5F5mNhmcJEf-IQGqilCyLRASmZmgd6_x5La_JoZSejSaYX1Bs1hGhGnS5IBrl5Ze81IMt3R8hP0HKzeoNdVba_ds1T2Tm2cXBzSJUf0rroI8'),
(2, 'AeroPress', 'Versatile and full-bodied immersion brew with a rich, smooth finish.', 6.25, 'https://lh3.googleusercontent.com/aida-public/AB6AXuC-kJRCMXEdnp8ADnvA4wP8k5h3yOWv0JOXgbRZKOn3LMunCmBQv1TQ4d1Q4lVu_736CWYetCAEK1vZnqy9XOJdsf1ZqpbMfQgorXWFxGWRAAdN1XW8isoab2TNPpqhFnJtoDBZrTHll0Noofln2mJkuYSwFvPO5ShBRug9x3K5mkJhQDLc4oBQB-plAkWpg-lmYZWzrQHOd9YmFmsoSu2rUVuPv85i4t9S_ivgmTjpEp7Jl_S3tzTX6QL96Tc2GMDb8_kzI8JHKms'),

-- Signature Specials
(3, 'Signature Pandan', 'Our cult favorite with aromatic pandan leaves and organic coconut milk.', 7.00, 'https://lh3.googleusercontent.com/aida-public/AB6AXuCOrkkMrvF49_SR6Fx439a85N7hOQkFY2gpg2m6QOzRvgaNu6ROo9gsGFvPmPacANaRLXpT6kVCi75Bkz_ZOn-PpgLzuYr4JgKvRaZ4jc_Xo9Wj41_eIkdJ_FMJRnPsWzoy5IP_B2CHlEc0pXDiEKoxrG1eKml6Ik2oqepU93lT8tfZDehUbcGFlwjsb9xeMHKsnikI_PM_OWAKECz8GuYuNUCfsO8hGa9VcOphbJ0C-6bG49mWNDkYizk5R36N5VeJ_qY_6uKpwg4'),
(3, 'Cloud Iced Latte', 'Chilled espresso over creamy cold milk and artisanal house syrup.', 6.25, 'https://lh3.googleusercontent.com/aida-public/AB6AXuCEXz7O5lakz6_nhfZ8jkNoipPO-tWt1NwP0Po3CJ5un_d1tv-NuYmQbH5_5yeeZyhoqMc_Ymj8Eo6S9j0gA8wlrhO7mK-kEEo8okVKznnP6gP4doBoqdXvYHnSdMQmdY7oW5ksTEblCt-fbCkDV40egFufRncvMfwbEFUjASX4n1mfkokXDtA_yfIKCssdPFixdki-we0cJFOneZyqA3G1hGOu4DxkxY9OubJpE9IAUIDun1IfQwZgqdeImMMNrE5g9kWZeyI50uM'),
(3, 'Velvet Hot Chocolate', 'Crafted from 70% dark single-origin cacao. Intense, silky, and deeply comforting.', 5.50, 'https://lh3.googleusercontent.com/aida-public/AB6AXuAQXaDktHsNYocCSKlaGXmuVnZh-S1tJtIRMwhzzHTH9_ySrKJBIUyE_iHtbJY-GNPtip56WkKVZINtn4OV-SEU75_DaWYkuw0n53m5KkN_pyjbq8DVJjX0TJaCps6a_0ARgIY5B2soJoDCuVPVv4a5UGB6dDBM-lA5Jsx7yPTUG_jEmeJIbWUCPmkW4c5g8K9IQOQF1oE7Yd47n3jxyRaNZzHg0W486iwq6KP6uzIl-r9aA1rdomT0iC46wa8VRvOQLPV_lKX4dGI'),
(3, 'Midnight Cold Brew', '18-hour slow-steeped concentrate, incredibly smooth with cocoa notes.', 6.50, 'https://lh3.googleusercontent.com/aida-public/AB6AXuDArDh6AmRKoh-GW9cuqGjWiodhr3dirovuHgt-Pc7BETnqHuNHS-nBLieCkOFkJfP_JP9vHd7iGOPg1XuoGRX8Z9HQ2Yl6cBDBo46kZbLms8DSx8FcszXwSmy7JV0cefW1kZ-avTvvmcab7bG3FzM-UnvFySphL7wsXgK9x5kF7NyqtBdSRi6pkp8CB3OVFXPR6oNA0gyqy3n4epHxGs0EzmJdSNJpmE6s8S0MqU3Pkbd8O_OGRQzCRSE75DQkormhg-obM6GkT9Q');
