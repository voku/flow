<?php

declare(strict_types=1);

use function Flow\ETL\DSL\ref;
use Flow\ETL\DSL\To;
use Flow\ETL\DSL\XML;
use Flow\ETL\Flow;
use Flow\ETL\GroupBy\Aggregation;

require __DIR__ . '/../../../bootstrap.php';

print "Reading XML dataset...\n";

return (new Flow())
    ->read(XML::from(__FLOW_DATA__ . '/salaries.xml'))
    ->withEntry('months', ref('row')->xpath('/Salaries/Month'))
    ->withEntry('month', ref('months')->expand())
    ->withEntry('month_name', ref('month')->domNodeAttribute('name'))
    ->withEntry('departments', ref('month')->xpath('/Month/Department'))
    ->withEntry('department', ref('departments')->expand())
    ->withEntry('department_name', ref('department')->domNodeAttribute('name'))
    ->withEntry('department_salary', ref('department')->xpath('/Department/TotalSalary')->domNodeValue())
    ->drop('row', 'months', 'month', 'departments', 'department')
    ->groupBy(ref('month_name'))
    ->aggregate(Aggregation::sum(ref('department_salary')))
    ->rename('department_salary_sum', 'total_monthly_salaries')
    ->write(To::output(false));
