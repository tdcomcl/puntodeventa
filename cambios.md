<!-- Ayuda de memoria 

git add .
git commit -m "cometario"
git push  (subir desde servidor a repositorio)


git pull (desde repo a servidor para actualizar)

Crear esta tabla en versiones anteriores. 
ALTER TABLE `ospos_stock_locations` ADD `CdgSIISucur` VARCHAR(800) NOT NULL AFTER `deleted`;

Cambiar numero de version en /applicaction/congig/config.php
 -->

Version: 3.3.5 
-  se crea log cuando de reimprime una boleta. 
- crear tabla ! 
CREATE TABLE `ospos_logImpresion` (
  `id` int(11) NOT NULL,
  `employee` varchar(500) NOT NULL,
  `sale_id_num` varchar(100) NOT NULL,
  `cash_total` varchar(300) NOT NULL,
  `transaction_time` varchar(200) NOT NULL,
  `payments` varchar(300) NOT NULL,
  `fechaImpresion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reimpresor` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




