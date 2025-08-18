CREATE TABLE public.ingredients (
	id serial4 NOT NULL,
	"name" varchar(100) NOT NULL,
	available_quantity int4 DEFAULT 0 NOT NULL,
	created_at timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	updated_at timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	CONSTRAINT ingredients_pkey PRIMARY KEY (id)
);
CREATE TABLE public.inventory_movements (
	id serial4 NOT NULL,
	ingredient_id int4 NULL,
	quantity int4 NOT NULL,
	"type" varchar(50) NOT NULL,
	created_at timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	CONSTRAINT inventory_movements_pkey PRIMARY KEY (id)
);


ALTER TABLE public.inventory_movements ADD CONSTRAINT inventory_movements_ingredient_id_fkey FOREIGN KEY (ingredient_id) REFERENCES public.ingredients(id) ON DELETE CASCADE;

-- INSERTS 
-- INGREDIENTS
INSERT INTO public.ingredients ("name",available_quantity,created_at,updated_at) VALUES
	 ('tomato',5,'2024-09-19 16:04:09.200158','2024-09-23 20:23:57'),
	 ('onion',5,'2024-09-19 16:04:25.284879','2024-09-23 20:23:57'),
	 ('meat',5,'2024-09-19 16:04:30.157484','2024-09-22 22:38:27'),
	 ('cheese',5,'2024-09-19 16:04:27.830709','2024-09-22 22:38:27'),
	 ('ketchup',5,'2024-09-19 16:04:20.54089','2024-09-22 22:38:27'),
	 ('chicken',5,'2024-09-19 16:04:32.629756','2024-09-23 03:44:28'),
	 ('lettuce',5,'2024-09-19 16:04:23.009108','2024-09-23 03:44:28'),
	 ('potato',5,'2024-09-19 16:04:15.478121','2024-09-23 03:44:38'),
	 ('rice',5,'2024-09-19 16:04:17.889271','2024-09-23 03:44:44'),
	 ('lemon',5,'2024-09-19 16:04:13.084195','2024-09-23 03:44:47');

