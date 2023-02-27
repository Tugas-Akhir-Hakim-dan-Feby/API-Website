<?php

namespace App\Console\Commands;

use App\Http\Traits\NamespaceFixer;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class MakeFilter extends Command
{
    use NamespaceFixer;

    protected $basePath = 'App\Http\Filters';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:filter {class : The name of the migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new filter class';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filter = $this->argument('class');

        if ($filter === '' || is_null($filter) || empty($filter)) {
            $this->error('Http Search Filter Name Invalid..!');
        }

        // create if folder Searches not exists
        if (!File::exists($this->getBaseDirectory($filter))) {
            File::makeDirectory($this->getBaseDirectory($filter), 0775, true);
        }

        $title = title($filter);
        $baseName = $this->getBaseFileName($filter);

        $filterPath = 'app/Http/Filters/' . $title;
        $filePath = $filterPath . '.php';
        $filterNameSpacePath = $this->getNameSpacePath($this->getNameSpace($filterPath));

        if (!File::exists($filePath)) {
            $eloquentFileContent = "<?php\nnamespace " . $filterNameSpacePath . ";\n\nuse Closure;\nuse Illuminate\Database\Eloquent\Builder;\n\nclass " . $baseName . "\n{\n\tprotected \$" . Str::camel($baseName) . ";\n\n\tpublic function __construct(\$" . Str::camel($baseName) . ")\n\t{\n\t\t\$this->" . Str::camel($baseName) . " = \$" . Str::camel($baseName) . ";\n\t}\n\n\tpublic function handle(Builder \$query, Closure \$next)\n\t{\n\t\tif (!\$this->keyword()) {\n\t\t\treturn \$next(\$query);\n\t\t}\n\t\t\$query->where('" . Str::snake($baseName) . "', 'LIKE', '%' . \$this->" . Str::camel($baseName) . " . '%');\n\n\t\treturn \$next(\$query);\n\t}\n\n\tprotected function keyword()\n\t{\n\t\tif (\$this->" . Str::camel($baseName) . ") {\n\t\t\treturn \$this->" . Str::camel($baseName) . ";\n\t\t}\n\n\t\t\$this->" . Str::camel($baseName) . " = request('" . Str::snake($baseName) . "', null);\n\n\t\treturn request('" . Str::snake($baseName) . "');\n\t}\n}";

            File::put($filePath, $eloquentFileContent);

            $this->info('filter created successfully...!');
        } else {
            $this->error('filter already exist...!');
        }
    }
}
