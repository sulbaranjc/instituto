<?php
require_once 'Database.php'; // Incluir la configuración de la base de datos
// Para utilizar la conexión en otro archivo, llamar a:
$conn = Database::getInstance()->getConnection(); //

//Database::getInstance(): Este es un ejemplo de un patrón de diseño Singleton en PHP. El Singleton es un patrón de diseño que restringe la instanciación de una clase a un único objeto. Se utiliza para proporcionar un punto de acceso global a la instancia, lo que puede ser útil para cosas como conexiones a bases de datos. En este caso, Database::getInstance() está llamando a la función getInstance() dentro de la clase Database, que devolverá una instancia de la clase Database.

//->getConnection(): Una vez que tenemos una instancia de la clase Database, llamamos al método getConnection(). Este método se encargará de establecer y devolver una conexión a la base de datos.

//$conn = ...: Finalmente, la conexión a la base de datos que se devuelve se almacena en la variable $conn. Esta variable se puede utilizar luego para realizar consultas y otras operaciones en la base de datos.

//Por lo tanto, esta línea de código es una forma eficiente de obtener una conexión a una base de datos en PHP utilizando el patrón de diseño Singleton.

// Ya puedes utilizar $conn para realizar operaciones con la base de datos
