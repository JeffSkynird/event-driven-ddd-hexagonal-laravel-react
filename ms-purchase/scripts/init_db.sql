CREATE TABLE public.purchases (
	id serial4 NOT NULL,
	quantity_purchased int4 NOT NULL,
	purchase_date timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	status varchar(50) NOT NULL,
	created_at timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	updated_at timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	ingredient_name varchar(100) NULL,
	quantity_requested int4 NULL,
	CONSTRAINT purchases_pkey PRIMARY KEY (id)
);

CREATE TABLE public.market_transactions (
	id serial4 NOT NULL,
	ingredient_name varchar(100) NOT NULL,
	quantity_requested int4 NOT NULL,
	quantity_received int4 NOT NULL,
	market_response jsonb NOT NULL,
	created_at timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	updated_at timestamp DEFAULT CURRENT_TIMESTAMP NULL,
	CONSTRAINT market_transactions_pkey PRIMARY KEY (id)
);