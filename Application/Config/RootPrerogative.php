<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 3/25/18
 * Time: 4:22 PM
 */


print '<h3>Inserting the Root Prerogative Databases</h3>';

########################### carbon_menu     categories
try {
    $db->prepare('SELECT 1 FROM carbon_menu LIMIT 1;')->execute();
    print '<br>Table `carbon_menu` already exists';
} catch (PDOException $e) {
    $sql = <<<END

create table carbon_menu
(
    category_id VARCHAR(225) NOT NULL,
	category_name VARCHAR(40) null,
	category_description text null,
	CONSTRAINT entity_menu_categories_entity_parent_pk_fk
		FOREIGN KEY (category_id) REFERENCES carbon (entity_pk)
			ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1
;
    
CREATE INDEX entity_comments_entity_parent_pk_fk
	ON  carbon_menu (category_id)
;

END;
    $db->exec($sql);
    print '<br>Table `carbon_menu` Created';
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


########################### menu_items

try {
    $db->prepare('SELECT 1 FROM menu_items LIMIT 1;')->execute();
    print '<br>Table `menu_items` already exists';
} catch (PDOException $e) {
    $sql = <<<END

create table menu_items
(
	item_id varchar(225) null,
	item_name varchar(20) null,
	item_description text null,
	item_price varchar(6) null,
	item_calories varchar(5) null,
	CONSTRAINT entity_menu_items_entity_parent_pk_fk
		FOREIGN KEY (item_id) REFERENCES carbon (entity_pk)
			ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1
;

END;

    $db->exec($sql);
    print '<br>Table `menu_items` Created';
}

Try {
    $sql = <<<END
REPLACE INTO carbon_tags (tag_id, tag_description, tag_name) VALUES (?,?,?);
END;

    $tag = [
        [MENU,'','MENU'],
        [ITEMS, '', 'ITEMS'],
    ];

    foreach ($tag as $key => $value) {
        $db->prepare($sql)->execute($value);
    }

    print '<br><br>Tags inserted';

} catch (PDOException $e) {
    print '<br>' . $e->getMessage();
}


