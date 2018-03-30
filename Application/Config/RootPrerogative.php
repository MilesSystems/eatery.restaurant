<?php
/**
 * Created by IntelliJ IDEA.
 * User: richardmiles
 * Date: 3/25/18
 * Time: 4:22 PM
 */


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











