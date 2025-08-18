CREATE TABLE public.dishes (
	id bigserial NOT NULL,
	"name" varchar(255) NOT NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT dishes_pkey PRIMARY KEY (id)
);


CREATE TABLE public.ingredients (
	id bigserial NOT NULL,
	"name" varchar(255) NOT NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT ingredients_pkey PRIMARY KEY (id)
);

CREATE TABLE public.orders (
	id serial4 NOT NULL,
	dish_id int4 NOT NULL,
	order_status varchar(20) DEFAULT 'pending'::character varying NOT NULL,
	created_at timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	updated_at timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	CONSTRAINT orders_pkey PRIMARY KEY (id)
);

CREATE TABLE public.recipes (
	dish_id int4 NOT NULL,
	ingredient_id int4 NOT NULL,
	quantity int4 NOT NULL,
	CONSTRAINT recipes_pkey PRIMARY KEY (dish_id, ingredient_id)
);


-- public.recipes foreign keys
ALTER TABLE public.recipes ADD CONSTRAINT recipes_dish_id_fkey FOREIGN KEY (dish_id) REFERENCES public.dishes(id) ON DELETE CASCADE;
ALTER TABLE public.recipes ADD CONSTRAINT recipes_ingredient_id_fkey FOREIGN KEY (ingredient_id) REFERENCES public.ingredients(id) ON DELETE CASCADE;


-- INSERTS 
-- dishes
INSERT INTO public.dishes ("name",created_at,updated_at) VALUES
	 ('Chicken Salad',NULL,NULL),
	 ('Beef Burger',NULL,NULL),
	 ('Tomato Soup',NULL,NULL),
	 ('Lemon Rice',NULL,NULL),
	 ('Potato Fries',NULL,NULL),
	 ('Cheese Omelette',NULL,NULL);

-- ingredients
INSERT INTO public.ingredients ("name",created_at,updated_at) VALUES
	 ('meat',NULL,'2024-09-19 06:24:37'),
	 ('cheese',NULL,'2024-09-19 06:24:37'),
	 ('ketchup',NULL,'2024-09-19 06:24:37'),
	 ('rice',NULL,'2024-09-19 14:50:30'),
	 ('lemon',NULL,'2024-09-19 14:50:30'),
	 ('onion',NULL,'2024-09-19 14:56:32'),
	 ('chicken',NULL,'2024-09-19 14:56:55'),
	 ('lettuce',NULL,'2024-09-19 14:56:55'),
	 ('tomato',NULL,'2024-09-19 14:56:55'),
	 ('potato',NULL,'2024-09-19 14:59:46');

-- recipes
INSERT INTO public.recipes (dish_id,ingredient_id,quantity) VALUES
	 ((select id from dishes where name = 'Chicken Salad'),(select id from ingredients where name = 'chicken'),1),
	 ((select id from dishes where name = 'Chicken Salad'),(select id from ingredients where name = 'lettuce'),1),
	 ((select id from dishes where name = 'Chicken Salad'),(select id from ingredients where name = 'tomato'),2),
	 ((select id from dishes where name = 'Beef Burger'),(select id from ingredients where name = 'meat'),1),
	 ((select id from dishes where name = 'Beef Burger'),(select id from ingredients where name = 'cheese'),1),
	 ((select id from dishes where name = 'Beef Burger'),(select id from ingredients where name = 'onion'),1),
	 ((select id from dishes where name = 'Beef Burger'),(select id from ingredients where name = 'ketchup'),1),
	 ((select id from dishes where name = 'Tomato Soup'),(select id from ingredients where name = 'tomato'),3),
	 ((select id from dishes where name = 'Tomato Soup'),(select id from ingredients where name = 'onion'),1),
	 ((select id from dishes where name = 'Lemon Rice'),(select id from ingredients where name = 'rice'),2),
	 ((select id from dishes where name = 'Lemon Rice'),(select id from ingredients where name = 'lemon'),1),
	 ((select id from dishes where name = 'Potato Fries'),(select id from ingredients where name = 'potato'),3);
     
INSERT INTO public.recipes (dish_id,ingredient_id,quantity) VALUES
	 ((select id from dishes where name = 'Cheese Omelette'),(select id from ingredients where name = 'cheese'),5),
	 ((select id from dishes where name = 'Cheese Omelette'),(select id from ingredients where name = 'onion'),5),
	 ((select id from dishes where name = 'Cheese Omelette'),(select id from ingredients where name = 'tomato'),5);
