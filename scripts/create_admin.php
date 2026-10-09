<?php
declare(strict_types=1);
if(PHP_SAPI!=='cli')exit(1);
require dirname(__DIR__).'/src/app.php';
require dirname(__DIR__).'/src/school.php';
if($argc!==3){fwrite(STDERR,"Usage: php scripts/create_admin.php IDENTIFIANT 'Nom complet'\nLe mot de passe sera demandé via STDIN.\n");exit(1);}
fwrite(STDOUT,"Mot de passe de 12 caractères minimum : ");
$password=trim((string)fgets(STDIN));
try{$id=create_user(db(),$argv[1],$argv[2],'admin',$password);echo "Administrateur créé (id=$id).\n";}
catch(Throwable $e){fwrite(STDERR,$e->getMessage()."\n");exit(1);}

