<?php
/** op-unit-model:/init.php
 *
 * @created     2023-12-19
 * @version     1.0
 * @package     op-unit-model
 * @author      Tomoaki Nagahara <tomoaki.nagahara@gmail.com>
 * @copyright   Tomoaki Nagahara All right reserved.
 */

/** Declare strict
 *
 */
declare(strict_types=1);

/** namespace
 *
 */
namespace OP;

//	Init.
$branch = $_SERVER['argv'][1] ?? null;
$name = basename(__DIR__);
$Name = ucfirst($name);
$NAME = strtoupper($name);

//	Validation.
if( empty($branch) ){
	echo "Empty the Branch name.\n";
	exit(__LINE__);
}

//	Init git branch.
echo `git branch {$branch}`;
echo `git switch {$branch}`;

//	Change the model name.
echo `git mv Model.class.php {$Name}.class.php`;
echo `git mv ci/Model.php ci/{$Name}.php`;

//	Get php files.
$list =  array_merge(glob('*.php'), glob('./ci/*.php'));
$list[] = 'README.md';

//	Loop all php files.
foreach( $list as $file_name ){
	//	...
	if( $file_name === basename(__FILE__) ){
		continue;
	}
	//	...
	$file = file_get_contents($file_name);
	$file = str_replace('model', $name, $file);
	$file = str_replace('Model', $Name, $file);
	$file = str_replace('MODEL', $NAME, $file);
	file_put_contents($file_name, $file);
}

//	Commit to git.
echo `git add .`;
echo `git commit -m 'Fix: Model --> {$Name}'`;
echo `git remote rename origin model`;
