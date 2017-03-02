<?

$templates = array();
$rsTemplates = CSite::GetTemplateList('s1');
while ($item = $rsTemplates->Fetch())
	$templates[$item['TEMPLATE']] = array(
		'CONDITION' => $item['CONDITION'],
		'SORT' => $item['SORT'],
		'TEMPLATE' => $item['TEMPLATE'],
	);

if ($templates['admin'])
	echo "Служебный шаблон уже добавлен\n";
else
{
	if ($templates['first'])
		if ($templates['first']['SORT'] < 5)
			$templates['first']['SORT'] = 5;
	$templates['admin'] = array(
		'CONDITION' => "CSite::InDir('/admin/')",
		'SORT' => 1,
		'TEMPLATE' => 'admin',
	);
	$templates = array_values($templates);
	$obSite = new CSite();
	$t = $obSite->Update('s1', array(
		'TEMPLATE' => $templates,
	));
	echo "Добавлен служебный шаблон\n";
}

echo "\n";

