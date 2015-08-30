<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TaxonomiesTable extends Migration
{
    /**
     * Table names
     */
    private $table_terms;
    private $table_taxonomies;
    private $table_pivot;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->table_terms      = config('taxonomies.table_terms');
        $this->table_taxonomies = config('taxonomies.table_taxonomies');
        $this->table_pivot      = config('taxonomies.table_pivot');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table_terms, function(Blueprint $table)
        {
            $table->increments('id');

            $table->string('name')->unique();
            $table->string('slug');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create($this->table_taxonomies, function(Blueprint $table)
        {
            $table->increments('id');

            $table->integer('term_id')->unsigned()->index();
            $table->foreign('term_id')
                ->references('id')
                ->on('terms');

            $table->string('taxonomy');
            $table->string('desc');

            $table->integer('parent')->unsigned()->default(0);

            $table->smallInteger('order')->unsigned()->default(0);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('terms_relations', function(Blueprint $table)
        {
            $table->increments('id');

            $table->integer('taxonomy_id')->unsigned()->index();
            $table->foreign('taxonomy_id')
                ->references('id')
                ->on('terms_taxonomies');

            $table->integer('taxable_id')->unsigned()->index();
            $table->string('taxable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->table_pivot);
        Schema::drop($this->table_taxonomies);
        Schema::drop($this->table_terms);
    }
}