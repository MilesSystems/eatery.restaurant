<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 3/25/18
 * Time: 4:22 PM
 */


print '<h3>Inserting the Root Prerogative Databases</h3>';


########################### menu_items

try {
    $db->prepare('SELECT 1 FROM carbon_category LIMIT 1;')->execute();
    print '<br>Table `carbon_category` already exists';
} catch (PDOException $e) {
    $sql = <<<END

create table carbon_category
(
	category_id varchar(225) not null,
	category_name varchar(225) null,
	category_description text null,
	category_tag varchar(40) null,
	constraint entity_menu_categories_entity_parent_pk_fk
		foreign key (category_id) references carbon (entity_pk)
			on update cascade on delete cascade
)
;

create index entity_comments_entity_parent_pk_fk
	on carbon_category (category_id)
;



END;

    $db->exec($sql);
    print '<br>Table `carbon_category` Created';
}



########################### category_items     categories
try {
    $db->prepare('SELECT 1 FROM category_items LIMIT 1;')->execute();
    print '<br>Table `category_items` already exists';
} catch (PDOException $e) {
    $sql = <<<END

create table category_items
(
	item_id varchar(225) null,
	item_name varchar(20) null,
	item_description text null,
	item_price varchar(6) null,
	item_calories varchar(5) null,
	constraint entity_menu_items_entity_parent_pk_fk
		foreign key (item_id) references carbon (entity_pk)
			on update cascade on delete cascade
)
;

create index entity_menu_items_entity_parent_pk_fk
	on category_items (item_id)
;



END;
    $db->exec($sql);
    print '<br>Table `category_items` Created';
}


########################### carbon_orders
try {
    $db->prepare('SELECT 1 FROM carbon_orders LIMIT 1;')->execute();
    print '<br>Table `carbon_orders` already exists';
} catch (PDOException $e) {

    $sql = <<<END

CREATE TABLE carbon_orders
(
    order_id VARCHAR(225),
    order_total INT(11),
    order_items TEXT,
    order_datetime DATETIME,
    order_costumer VARCHAR(225),
    order_server VARCHAR(225),
    order_chef VARCHAR(225),
    order_notes TEXT,
    CONSTRAINT carbon_orders_carbon_entity_pk_fk FOREIGN KEY (order_id) REFERENCES carbon (entity_pk),
    CONSTRAINT carbon_costumer_carbon_entity_pk_fk FOREIGN KEY (order_costumer) REFERENCES carbon (entity_pk),
    CONSTRAINT carbon_server_carbon_entity_pk_fk FOREIGN KEY (order_server) REFERENCES carbon (entity_pk),
    CONSTRAINT carbon_chef_carbon_entity_pk_fk FOREIGN KEY (order_chef) REFERENCES carbon (entity_pk)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
;

END;

    $db->exec($sql);
    print '<br>Table `carbon_orders` Created';
}




########################### carbon_cart
try {
    $db->prepare('SELECT 1 FROM carbon_cart LIMIT 1;')->execute();
    print '<br>Table `carbon_cart` already exists';
} catch (PDOException $e) {

    $sql = <<<END

create table carbon_cart
(
	cart_id varchar(225) null,
	cart_item varchar(225) not null,
	session_id varchar(225) not null,
	cart_notes text null,
	CONSTRAINT carbon_cart_carbon_entity_pk_fk FOREIGN KEY (cart_id) REFERENCES carbon (entity_pk)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
;

END;

    $db->exec($sql);
    print '<br>Table `carbon_cart` Created';
}

########################### user_sessions
try {
    $db->prepare('SELECT 1 FROM user_sessions LIMIT 1;')->execute();
    print '<br>Table `user_sessions` already exists';
} catch (PDOException $e) {

    $sql = <<<END

CREATE TABLE user_sessions
(
	user_id VARCHAR(225) NULL,
	time_in DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1
;

END;

    $db->exec($sql);
    print '<br>Table `carbon_cart` Created';
}


Try {
    $sql = <<<END
REPLACE INTO carbon_tags (tag_id, tag_description, tag_name) VALUES (?,?,?);
END;

    $tag = [
        [CATEGORY,'','CATEGORY'],
        [ITEMS, '', 'ITEMS'],
        [ORDER, '', 'ORDER'],
        [CART, '', 'CART']
    ];

    foreach ($tag as $key => $value) {
        $db->prepare($sql)->execute($value);
    }

    print '<br><br>Tags inserted';

} catch (PDOException $e) {
    print '<br>' . $e->getMessage();
}


