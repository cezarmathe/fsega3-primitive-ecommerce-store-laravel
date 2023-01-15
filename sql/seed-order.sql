create extension  if not exists tsm_system_rows;

with
    sample_products as (
        select id
        from products tablesample system_rows(10)
        limit 10
    ),
    sample_user as (
        select id
        from users tablesample system_rows(3)
    ),
    new_orders as (
        insert into orders (user_id, total, created_at, updated_at)
        select id, 0.0, localtimestamp, localtimestamp
        from sample_user
        returning id
    ),
    new_order_items as (
        insert into order_items (order_id, product_id, quantity, created_at, updated_at)
        select o.id, p.id, 1.00, localtimestamp, localtimestamp
        from new_orders o, sample_products p
        returning id
    )
select count(id) from new_order_items;
-- update orders
-- set total = oi.total
-- from (
--     select sum(oi.quantity * p.price) as total, order_id
--     from order_items oi
--     join new_order_items noi on noi.id = oi.id
--     join products p on p.id = oi.product_id
--     group by oi.order_id
-- ) as oi
-- join new_orders nos on nos.id = oi.order_id
