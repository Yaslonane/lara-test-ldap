<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ModuleMake extends Command
{
    private $files;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name}
                                            {--all}
                                            {--migration}
                                            {--view}
                                            {--controller}
                                            {--model}
                                            {--api}
                                            {--vue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    //php artisan make:module Admin/Courses --all
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->files = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if($this->option( key: 'all')){
            $this->input->setOption(name: 'model', value: true);
            $this->input->setOption(name: 'controller', value: true);
            $this->input->setOption(name: 'view', value: true);
            $this->input->setOption(name: 'migration', value: true);
            $this->input->setOption(name: 'api', value: true);
        }

        if($this->option( key: 'model')){
            $this->createModel();
        }
        if($this->option( key: 'controller')){
            $this->createController();
        }
        if($this->option( key: 'view')){
            $this->createView();
        }
        if($this->option( key: 'migration')){
            $this->createMigration();
        }
        if($this->option( key: 'api')){
            $this->createApiController();
        }
        if($this->option( key: 'vue')){
            $this->createVue();
        }
    }

    private function createModel (){
        $modelName = Str::singular(Str::studly(class_basename($this->argument( key: 'name'))));

        $this->call('make:model', [
            'name' => "App\\Modules\\".trim($this->argument( key: 'name'))."\\Models\\".$modelName
        ]);
        //echo $model;
    }

    private function createController (){
        $controller = Str::studly(class_basename($this->argument( key: 'name')));
        $modelName = Str::singular(Str::studly(class_basename($this->argument( key: 'name'))));

        $path = $this->getControllerPath($this->argument( key: 'name'));

        if ($this->alreadyExists( path: $path)) {
            $this->error( string: 'Controller already exists!');
        } else {
            $this->makeDirectory($path);

            $stub = $this->files->get(base_path( path: 'resources/stubs/controller.model.api.stub'));

            $stub = str_replace(
                [
                    'DummyNamespace',
                    'DummyRootNamespace',
                    'DummyClass',
                    'DummyFullModelClass',
                    'DummyModelClass',
                    'DummyModelVariable',
                ],
                [
                    "App\\Modules\\".str_replace( search: '/', replace: '\\', subject: trim($this->argument( key: 'name')))."\\Controllers",
                    $this->laravel->getNamespace(),
                    $controller.'Controller',
                    "App\\Modules\\".str_replace( search: '/', replace: '\\', subject: trim($this->argument( key: 'name')))."\\Models\\{$modelName}",
                    $modelName,
                    lcfirst(($modelName))
                ],
                $stub
            );

            $this->files->put($path, $stub);
            $this->info( string: 'Controller created successfully.');
            //$this->updateModularConfig();
        }

        $this->createRoutes($controller, $modelName);
    }

    private function createView (){
        $paths = $this->getViewPath($this->argument('name'));

        foreach ($paths as $path) {
            $view = Str::studly(class_basename($this->argument('name')));

            if ($this->alreadyExists($path)) {
                $this->error('View already exists!');
            } else {
                $this->makeDirectory($path);

                $stub = $this->files->get(base_path('resources/stubs/view.stub'));

                $stub = str_replace(
                    [
                        '',
                    ],
                    [
                    ],
                    $stub
                );

                $this->files->put($path, $stub);
                $this->info('View created successfully.');
            }
        }
    }

    private function createMigration (){
        $table = Str::plural(class_basename($this->argument( key: 'name')));
        //$table_prefix = Str::before($this->argument( key: 'name'), '/'.class_basename($this->argument( key: 'name'))); //Добавляет префикс на основе родительской директории модуля
        try{
            $this->call('make:migration', [
                'name' => "create{$table}_table",
                '--create' => /*Str::upper($table_prefix)."_".*/$table,                                                  // подставляет префикс
                '--path' => "app/Modules/".trim($this->argument( key: 'name'))."/Migrations"
            ]);
        }catch(\Exception $e){
            $this->error($e->getMessage());
        }
        //echo " | ".$table."+ prefix ".$table_prefix;
    }

    private function createApiController (){
        $controller = Str::studly(class_basename($this->argument( key: 'name')));
        $modelName = Str::singular(Str::studly(class_basename($this->argument( key: 'name'))));

        $path = $this->getApiControllerPath($this->argument( key: 'name'));

        if ($this->alreadyExists( path: $path)) {
            $this->error( string: 'Controller already exists!');
        } else {
            $this->makeDirectory($path);

            $stub = $this->files->get(base_path( path: 'resources/stubs/controller.model.api.stub'));

            $stub = str_replace(
                [
                    'DummyNamespace',
                    'DummyRootNamespace',
                    'DummyClass',
                    'DummyFullModelClass',
                    'DummyModelClass',
                    'DummyModelVariable',
                ],
                [
                    "App\\Modules\\".str_replace( search: '/', replace: '\\', subject: trim($this->argument( key: 'name')))."\\Controllers\\Api",
                    $this->laravel->getNamespace(),
                    $controller.'Controller',
                    "App\\Modules\\".str_replace( search: '/', replace: '\\', subject: trim($this->argument( key: 'name')))."\\Models\\{$modelName}",
                    $modelName,
                    lcfirst(($modelName))
                ],
                $stub
            );

            $this->files->put($path, $stub);
            $this->info( string: 'Controller created successfully.');
            //$this->updateModularConfig();
        }

        $this->createApiRoutes($controller, $modelName);
    }

    private function createVue (){
        $path = $this->getVueComponentPath($this->argument('name'));

        $component = Str::studly(class_basename($this->argument('name')));

        if ($this->alreadyExists($path)) {
            $this->error('Vue Component already exists!');
        } else {
            $this->makeDirectory($path);

            $stub = $this->files->get(base_path('resources/stubs/vue.component.stub'));

            $stub = str_replace(
                [
                    'DummyClass',
                ],
                [
                    $component,
                ],
                $stub
            );

            $this->files->put($path, $stub);
            $this->info('Vue Component created successfully.');
        }
    }

    private function createRoutes(String $controller, String $modelName) : void {
        $routePath = $this->getRoutesPath($this->argument('name'));

        if ($this->alreadyExists($routePath)) {
            $this->error('Routes already exists!');
        } else {

            $this->makeDirectory($routePath);

            $stub = $this->files->get(base_path('resources/stubs/routes.web.stub'));

            $stub = str_replace(
                [
                    'DummyClass',
                    'DummyRoutePrefix',
                    'DummyModelVariable',
                ],
                [
                    $controller.'Controller',
                    Str::plural(Str::snake(lcfirst($modelName), '-')),
                    lcfirst($modelName)
                ],
                $stub
            );

            $this->files->put($routePath, $stub);
            $this->info('Routes created successfully.');
        }
    }

    private function createApiRoutes(String $controller, String $modelName) : void {

        $routePath = $this->getApiRoutesPath($this->argument('name'));

        if ($this->alreadyExists($routePath)) {
            $this->error('Routes already exists!');
        } else {

            $this->makeDirectory($routePath);

            $stub = $this->files->get(base_path('resources/stubs/routes.api.stub'));

            $stub = str_replace(
                [
                    'DummyClass',
                    'DummyRoutePrefix',
                    'DummyModelVariable',
                ],
                [
                    'Api\\'.$controller.'Controller',
                    Str::plural(Str::snake(lcfirst($modelName), '-')),
                    lcfirst($modelName)
                ],
                $stub
            );

            $this->files->put($routePath, $stub);
            $this->info('Routes created successfully.');
        }

    }

    private function getControllerPath ($argument){
        $controller = Str::studly(class_basename($argument));

        return $this->laravel['path']."/Modules/". str_replace( search: '\\', replace: '/', subject: $argument) ."/Controllers/"."{$controller}Controller.php";
    }

    private function getApiControllerPath ($argument){

        $controller = Str::studly(class_basename($argument));
        return $this->laravel['path'].'/Modules/'.str_replace('\\', '/', $argument)."/Controllers/Api/"."{$controller}Controller.php";

    }

    private function makeDirectory ($path){
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    protected function alreadyExists($path) : bool {
        return $this->files->exists($path);
    }

    private function getApiRoutesPath($name) : string {
        return $this->laravel['path'].'/Modules/'.str_replace('\\', '/', $name)."/Routes/api.php";

    }

    private function getRoutesPath($name) : string {
        return $this->laravel['path'].'/Modules/'.str_replace('\\', '/', $name)."/Routes/web.php";

    }

    protected function getVueComponentPath($name) : String {
        return base_path('resources/js/components/'.str_replace('\\', '/', $name).".vue");
    }

    protected function getViewPath($name) : object {

        $arrFiles = collect([
            'create',
            'edit',
            'index',
            'show',
        ]);

        //str_replace('\\', '/', $name)
        $paths = $arrFiles->map(function($item) use ($name){
            return base_path('resources/views/'.str_replace('\\', '/', $name).'/'.$item.".blade.php");
        });

        return $paths;
    }

}
