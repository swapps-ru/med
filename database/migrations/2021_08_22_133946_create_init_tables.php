<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateInitTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();

            $table->text('desc_short')->nullable();

            $table->text('block_ids')->nullable();

            $table->string('disease_ids')->nullable();
            $table->string('syndrome_ids')->nullable();
            $table->string('symptom_ids')->nullable();
            $table->string('aid_ids')->nullable();

            $table->integer('views')->unsigned()->default(0);
            $table->integer('views_recently')->unsigned()->default(0);

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');

            $table->tinyInteger('is_published')->unsigned()->default(0);

            $table->timestamps();
        });

        \App\Models\User::create([
            'name' => 'admin',
            'email' => 'test@mail.ru',
            'password' => Hash::make('testpassword'),
        ]);


        DB::table('articles')->insert([
            'title' => 'Рассеяный склероз - статья',
            'desc_short' => 'Коротко',
            'block_ids' => '_1__2_',
            'disease_ids' => '_1_',
            'syndrome_ids' => '_1_',
            'symptom_ids' => '_1__2_',
            'aid_ids' => '_1__2_',
            'views' => 0,
            'views_recently' => 0,
            'user_id' => 1,
            'is_published' => 1,
            'created_at' => Carbon::now()
        ]);


        Schema::create('articles_history', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('article_id')->unsigned();
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('restrict');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');

            $table->longtext('blocks_json')->nullable();

            $table->timestamps();
        });

        Schema::create('body_systems', function (Blueprint $table) {
            $table->id();

            $table->string('names')->nullable()->unique();

            $table->string('icon_class')->nullable();

            $table->string('svg_url')->nullable();
            $table->string('svg_element_id')->nullable();
        });

        DB::table('body_systems')->insert([
            ['names' => 'Опорно-двигательная система', 'icon_class' => 'fa-'],
            ['names' => 'Пищеварительная система', 'icon_class' => 'fa-poop'],
            ['names' => 'Дыхательная система', 'icon_class' => 'fa-lungs'],
            ['names' => 'Мочевыделительная система', 'icon_class' => 'fa-'],
            ['names' => 'Женская репродуктивная система', 'icon_class' => 'fa-'],
            ['names' => 'Мужская репродуктивная система', 'icon_class' => 'fa-'],
            ['names' => 'Эндокринные железы', 'icon_class' => 'fa-'],
            ['names' => 'Система кровообращения', 'icon_class' => 'fa-'],
            ['names' => 'Лимфатическая система', 'icon_class' => 'fa-'],
            ['names' => 'Сердечно-сосудистая системы', 'icon_class' => 'fa-heartbeat'],
            ['names' => 'Центральная нервная система;ЦНС', 'icon_class' => 'fa-brain'],
            ['names' => 'Периферическая нервная система', 'icon_class' => 'fa-brain'],
            ['names' => 'Органы чувств', 'icon_class' => 'fa-'],
            ['names' => 'Покровная система', 'icon_class' => 'fa-allergies'],
        ]);


        Schema::create('disease_groups', function (Blueprint $table) {
            $table->id();
            $table->string('names')->nullable();
            $table->string('names_scientific')->nullable();

            $table->string('body_part_ids')->nullable(); //в следующей миграции переименуется в body_system_ids

            $table->string('slug')->nullable()->index(); //я вообще хз зачем добавил это поле, удалено в следующей миграции

            $table->timestamps();
        });

        DB::table('disease_groups')->insert([
            'names' => 'Нервные расстройства',
            'names_scientific' => 'Неврологические заболевания',
            'created_at' => Carbon::now(),
            'body_part_ids' => '_11__12_'
        ]);

        Schema::create('diseases', function (Blueprint $table) {
            $table->id();
            $table->string('names')->nullable();
            $table->string('names_scientific')->nullable();

            $table->string('body_part_ids')->nullable(); //в следующей миграции переименуется в body_system_ids

            $table->string('disease_groups_ids')->nullable();
            $table->string('diseases_complication_ids')->nullable();

            $table->integer('wordstat_queries')->default(0)->unsigned();
            $table->text('wordstat_query_names')->nullable();

            $table->bigInteger('article_main_id')->nullable()->unsigned();
            $table->foreign('article_main_id')->references('id')->on('articles')->onDelete('restrict');

            $table->timestamps();
        });


        DB::table('diseases')->insert([
            'names' => 'Рассеяный склероз;обычное название',
            'names_scientific' => 'Рассеяный склероз;научное название 2',
            'body_part_ids' => '_11__12_',
            'disease_groups_ids' => '_1_',
            'article_main_id' => 1,
            'wordstat_query_names' => 'рассеяный склероз',
            'created_at' => Carbon::now()
        ]);


        Schema::create('syndromes', function (Blueprint $table) {
            $table->id();
            $table->string('names')->nullable();
            $table->string('names_scientific')->nullable();

            $table->string('symptom_include_ids')->nullable(); //какие сиптомы включает болезнь

            $table->integer('wordstat_queries')->default(0)->unsigned();
            $table->text('wordstat_query_names')->nullable();

            $table->timestamps();
        });


        Schema::create('symptoms', function (Blueprint $table) {
            $table->id();
            $table->string('names')->nullable();
            $table->string('names_scientific')->nullable();

            $table->string('icon_class')->nullable();

            $table->string('body_part_ids')->nullable(); //в следующей миграции переименуется в body_system_ids
            $table->text('options_default_json')->nullable(); //какие "уточнения" симптома добавлять по-умолчанию в конструкторе

            $table->enum('type', ['pain', 'blood_test', 'measured', 'visual', 'other'])->default('other');

            $table->integer('wordstat_queries')->default(0)->unsigned();
            $table->text('wordstat_query_names')->nullable();

            $table->bigInteger('article_main_id')->nullable()->unsigned();
            $table->foreign('article_main_id')->references('id')->on('articles')->onDelete('restrict');

            $table->timestamps();
        });

        $symptoms = array_merge($this->getSymptomsParsed(), $this->getBloodTestsParsed());
        foreach ($symptoms as &$symptom) {
            $symptom['wordstat_query_names'] = trim($symptom['names'] . ', ' . $symptom['names_scientific'], ' ,');

            $symptom['created_at'] = Carbon::now();
            $symptom['updated_at'] = Carbon::now();

            if (empty($symptom['names_scientific'])) {
                $symptom['names_scientific'] = null;
            }
        }

        DB::table('symptoms')->insert($symptoms);

        DB::table('symptoms')->select('id')->where('id', 1)->update([
            'article_main_id' => 1,
            'options_default_json' => json_encode(['особенность 1', 'особенность 2'], JSON_UNESCAPED_UNICODE)
        ]);

        DB::table('symptoms')->select('id')->where('names', 'like', '%Онемение%')->update(['body_part_ids' => '_11__13__12_']);
        DB::table('symptoms')->select('id')->where('names', 'like', '%Нарушение письма%')->update(['body_part_ids' => '_11__13__12_']);
        DB::table('symptoms')->select('id')->where('names', 'like', '%Потеря памяти%')->update(['body_part_ids' => '_11__12_']);

        $symptomes_ids = [];
        $symptomes_ids[] = DB::table('symptoms')->select('id')->where('names', 'like', '%Боль в пояснице%')->first()->id;
        $symptomes_ids[] = DB::table('symptoms')->select('id')->where('names', 'like', '%Онемение%')->first()->id;
        $symptomes_ids[] = DB::table('symptoms')->select('id')->where('names', 'like', '%Мышечная слабость%')->first()->id;

        DB::table('syndromes')->insert(['names' => 'Радикулярный синдром', 'created_at' => Carbon::now(), 'symptom_include_ids' => '_' . implode('__', $symptomes_ids) . '_']);

        $symptomes_ids = [];
        $symptomes_ids[] = DB::table('symptoms')->select('id')->where('names', 'like', '%Кровь в моче%')->first()->id;
        $symptomes_ids[] = DB::table('symptoms')->select('id')->where('names', 'like', '%Отеки ног%')->first()->id;
        $symptomes_ids[] = DB::table('symptoms')->select('id')->where('names', 'like', '%Отеки рук%')->first()->id;
        $symptomes_ids[] = DB::table('symptoms')->select('id')->where('names', 'like', '%Отеки лица%')->first()->id;
        $symptomes_ids[] = DB::table('symptoms')->select('id')->where('names_scientific', 'like', '%Анурия%')->first()->id;

        DB::table('syndromes')->insert(['names' => 'Нефртический синдром', 'created_at' => Carbon::now(), 'symptom_include_ids' => '_' . implode('__', $symptomes_ids) . '_']);


        Schema::create('aid_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->unique();

            $table->text('desc_short')->nullable();
            $table->text('desc_full')->nullable();

            $table->timestamps();
        });

        DB::table('aid_groups')->insert([
            'name' => 'Группа лекарств',
            'desc_short' => 'Коротко',
            'desc_full' => 'Длинно',
        ]);

        DB::table('aid_groups')->insert([
            'name' => 'Группа лекарств 2',
            'desc_short' => 'Коротко 2',
            'desc_full' => 'Длинно 2',
        ]);

        Schema::create('aid_substances', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->unique();

            $table->text('desc_short')->nullable();
            $table->text('desc_full')->nullable();

            $table->bigInteger('aid_group_id')->unsigned();
            $table->foreign('aid_group_id')->references('id')->on('aid_groups')->onDelete('restrict');

            $table->string('restricted_aid_group_ids')->nullable();
            $table->string('restricted_aid_substance_ids')->nullable();

            $table->string('careful_aid_groups_ids')->nullable();
            $table->string('careful_aid_substance_ids')->nullable();


            $table->enum('allowed_pregnant', ['no', 'yes', 'careful', 'unknown'])->default('unknown')->index();
            $table->enum('allowed_alco', ['no', 'yes', 'careful', 'unknown'])->default('unknown')->index();
            $table->enum('allowed_driving', ['no', 'yes', 'careful', 'unknown'])->default('unknown')->index();

            $table->tinyInteger('allowed_age_min')->unsigned()->default(18);


            $table->timestamps();
        });

        DB::table('aid_substances')->insert([
            'name' => 'Действ. вещество',
            'desc_short' => 'Коротко',
            'desc_full' => 'Длинно',
            'aid_group_id' => 1,
            'restricted_aid_group_ids' => '_1_',
            'restricted_aid_substance_ids' => '_1_',
            'careful_aid_groups_ids' => '_2_',
            'careful_aid_substance_ids' => '_1_',
        ]);

        Schema::create('aids', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->unique();

            $table->bigInteger('aid_group_id')->unsigned();
            $table->foreign('aid_group_id')->references('id')->on('aid_groups')->onDelete('restrict');

            $table->string('aid_substance_ids')->nullable();

            $table->text('desc_short')->nullable();
            $table->text('desc_full')->nullable();

            $table->tinyInteger('popularity')->unsigned()->default(0);

            $table->timestamps();
        });

        DB::table('aids')->insert([
            'name' => 'Лекарство',
            'desc_short' => 'Коротко',
            'desc_full' => 'Длинно',
            'aid_group_id' => 1,
            'aid_substance_ids' => '_1_',
        ]);

        Schema::create('block_types', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();

            $table->string('react_module')->nullable();

            $table->string('icon_class')->nullable();
        });

        DB::table('block_types')->insert([
            'title' => 'Заголовок',
            'react_module' => 'Input',
            'icon_class' => 'fa fa-heading',
        ]);

        DB::table('block_types')->insert([
            'title' => 'Абзац',
            'react_module' => 'TextArea',
            'icon_class' => 'fa fa-align-right',
        ]);

        Schema::create('blocks', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('type_id')->unsigned();
            $table->foreign('type_id')->references('id')->on('block_types')->onDelete('restrict');

            $table->text('data_json')->nullable();

            $table->timestamps();
        });

        DB::table('blocks')->insert([
            'type_id' => 1,
            'data_json' => '{"html": "Рассеяный склероз"}'
        ]);

        DB::table('blocks')->insert([
            'type_id' => 2,
            'data_json' => '{"html": "Про рассеяный склероз"}',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        Schema::dropIfExists('body_systems');
        Schema::dropIfExists('disease_groups');
        Schema::dropIfExists('diseases');
        Schema::dropIfExists('syndromes');
        Schema::dropIfExists('symptoms');
        Schema::dropIfExists('aid_groups');
        Schema::dropIfExists('aid_substances');
        Schema::dropIfExists('aids');
        Schema::dropIfExists('block_types');
        Schema::dropIfExists('blocks');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('articles_history');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function getSymptomsParsed()
    {
        $list = file_get_contents(storage_path('app/') . '/symptoms_list_start.txt', true);
        $rows = explode("\n", $list);
        $rows_new = [];

        $row_new_last = null;
        foreach ($rows as $row) {
            if (count($rows_new) > 0) {
                $row_new_last = &$rows_new[array_key_last($rows_new)];
            }

            $row_clean = str_replace('+', '', trim(explode('.', $row, 2)[1] ?? explode('.', $row, 2)[0]));

            $is_have_spaces = mb_substr($row, 0, 3) === '   ';
            $is_location = mb_substr($row_clean, 0, 1) === '!';
            $is_option = mb_substr($row_clean, 0, 1) !== '!';

            if (!is_null($row_new_last) && $is_have_spaces) {
                //если больше пробелов в начале, значит это или локализация или специфика, добавляем к последнему добавленному симптому

                if (!isset($row_new_last['locations'])) {
                    $row_new_last['locations'] = [];
                    $row_new_last['options'] = [];
                }

                if ($is_location && !empty($row_clean)) {
                    $row_new_last['locations'][] = trim(str_replace('!', '', $row_clean));
                }

                if ($is_option && !empty($row_clean)) {
                    $row_new_last['options'][] = $row_clean;
                }

            } else {

                $row_new = ['names' => [], 'names_scientific' => []];
                $names = explode(',', $row_clean);
                $names_count = count($names);

                for ($i = 0; $i < $names_count; $i++) {

                    $names[$i] = trim($names[$i]);

                    $name = $names[$i];
                    $name_clean = mb_strtolower(str_replace(['+', '-'], '', $names[$i]));

                    $is_simple = ($names_count == 1) || ($names_count == 2 && $i == 1) || ($names_count > 2 && strpos(substr($name, 0, 2), '-') === false);

                    if (mb_strlen($name_clean) >= 2) {
                        $row_new[$is_simple ? 'names' : 'names_scientific'][] = $name_clean;
                    }
                }

                $rows_new[] = $row_new;
            }
        }

        $list_symptoms = [];
        foreach ($rows_new as $row) {

            $name_bases = &$row['names'];
            $name_scientific_bases = &$row['names_scientific'];

            $variants = [];
            $variants_scientific = [];

            $type = 'other';
            $string_imploded = implode(';', array_merge($name_bases, $name_scientific_bases));
            if (mb_stripos($string_imploded, 'боль') !== false || mb_stripos($string_imploded, 'боли') !== false) {
                $type = 'pain';
            } else if (mb_stripos($string_imploded, 'покрасн') !== false || mb_stripos($string_imploded, 'кожи') !== false || mb_stripos($string_imploded, 'сыпь') !== false) {
                $type = 'visual';
            }

            foreach ($name_bases as &$name_base) {
                $variants[] = Str::ucfirst($name_base);
            }

            foreach ($name_scientific_bases as &$name_scientific_base) {
                $variants_scientific[] = Str::ucfirst($name_scientific_base);
            }

            $list_symptoms[] = [
                'names' => implode(';', $variants),
                'names_scientific' => implode(';', $variants_scientific),
                'type' => $type
            ];


            if (!empty($row['locations'])) {
                //если есть локализация делаем набор сочетаний основного симптома и локализаций

                if (!empty($row['options'])) {
                    $row['options'] = array_merge([''], $row['options']); //чтобы был вариант без характера, только симптом + локализация
                }

                foreach ($row['locations'] as $location) {
                    $letter_first_location = mb_substr($location, 0, 1);
                    $is_first_upper_location = (mb_strtolower($letter_first_location) !== $letter_first_location);

                    if (!empty($row['options'])) {
                        //к комбинациям вариантов добавляем специфику симптомов, если она есть

                        foreach ($row['options'] as $option) {
                            $variants = [];
                            $variants_scientific = [];

                            $letter_first = mb_substr($option, 0, 1);
                            $is_first_upper = (mb_strtolower($letter_first) !== $letter_first);

                            foreach ($name_bases as &$name_base) {
                                $string_add = $is_first_upper_location ? Str::ucfirst($location . ' ' . mb_strtolower($name_base)) : mb_strtolower(mb_strtolower($name_base) . ' ' . $location);

                                if ($is_first_upper) {
                                    $variants[] = Str::ucfirst($option) . ' ' . mb_strtolower($string_add);
                                } else {
                                    $variants[] = Str::ucfirst($string_add) . ' ' . mb_strtolower($option);
                                }
                            }

                            foreach ($name_scientific_bases as &$name_scientific_base) {
                                if ($is_first_upper) {
                                    $variants_scientific[] = Str::ucfirst($option) . ' ' . mb_strtolower($location . ' ' . $name_scientific_base);
                                } else {
                                    $variants_scientific[] = Str::ucfirst($name_scientific_base) . ' ' . mb_strtolower($option);
                                }
                            }

                            $list_symptoms[] = [
                                'names' => implode(';', $variants),
                                'names_scientific' => implode(';', $variants_scientific),
                                'type' => $type
                            ];
                        }

                    } else {
                        $variants = [];
                        $variants_scientific = [];

                        foreach ($name_bases as &$name_base) {
                            if ($is_first_upper_location) {
                                //если заглавная буква, локализацию ставим перед названием симптома

                                $variants[] = Str::ucfirst($location) . ' ' . mb_strtolower($name_base);
                            } else {
                                $variants[] = Str::ucfirst($name_base) . ' ' . mb_strtolower($location);
                            }
                        }

                        foreach ($name_scientific_bases as &$name_scientific_base) {
                            if ($is_first_upper_location) {
                                $variants_scientific[] = Str::ucfirst($location) . ' ' . mb_strtolower($name_scientific_base);
                            } else {
                                $variants_scientific[] = Str::ucfirst($name_scientific_base) . ' ' . mb_strtolower($location);
                            }
                        }

                        $list_symptoms[] = [
                            'names' => implode(';', $variants),
                            'names_scientific' => implode(';', $variants_scientific),
                            'type' => $type
                        ];
                    }
                }
            } else if (empty($row['locations']) && !empty($row['options'])) {
                //просто добавляем специфику симптомов, если нет локализаций

                foreach ($row['options'] as $option) {
                    $variants = [];
                    $variants_scientific = [];

                    $letter_first = mb_substr($option, 0, 1);
                    $is_first_upper = (mb_strtolower($letter_first) !== $letter_first);

                    foreach ($name_bases as &$name_base) {
                        if ($is_first_upper) {
                            //если заглавная буква, локализацию ставим перед названием симптома

                            $variants[] = $option . ' ' . mb_strtolower($name_base);
                        } else {
                            $variants[] = Str::ucfirst($name_base) . ' ' . $option;
                        }
                    }

                    foreach ($name_scientific_bases as &$name_scientific_base) {
                        if ($is_first_upper) {
                            $variants_scientific[] = $option . ' ' . mb_strtolower($name_scientific_base);
                        } else {
                            $variants_scientific[] = Str::ucfirst($name_scientific_base) . ' ' . $option;
                        }
                    }

                    $list_symptoms[] = [
                        'names' => implode(';', $variants),
                        'names_scientific' => implode(';', $variants_scientific),
                        'type' => $type
                    ];
                }
            }
        }

        return $list_symptoms;
    }

    public function getBloodTestsParsed()
    {
        $list = file_get_contents(storage_path('app/') . '/blood_test_list_start.txt', true);
        $rows = explode("\n", $list);
        $rows_new = [];

        $row_new_last = null;

        foreach ($rows as $row) {
            if (count($rows_new) > 0) {
                $row_new_last = &$rows_new[array_key_last($rows_new)];
            }

            $row_clean = trim(explode('.', $row, 2)[1] ?? explode('.', $row, 2)[0]);

            $is_have_spaces = mb_substr($row, 0, 3) === '   ';

            if (!is_null($row_new_last) && $is_have_spaces) {
                //если больше пробелов в начале, значит это или локализация или специфика, добавляем к последнему добавленному симптому

                if (!isset($row_new_last['options'])) {
                    $row_new_last['options'] = [];
                }

                if (!empty($row_clean)) {
                    $row_new_last['options'][] = $row_clean;
                }
            } else {
                $row_new = ['names' => [], 'names_scientific' => []];

                $names = explode(';', $row_clean);
                $names_count = count($names);

                for ($i = 0; $i < $names_count; $i++) {

                    $names[$i] = trim($names[$i]);

                    $name = $names[$i];
                    $name = trim(explode('.', $name, 2)[1] ?? explode('.', $name, 2)[0]);

                    $letter_first = mb_substr($name, 0, 1);
                    $is_first_upper = (mb_strtolower($letter_first) !== $letter_first);
                    $name = !$is_first_upper ? Str::ucfirst($name) : $name;

                    $is_simple = ($names_count == 1) || ($names_count >= 2 && $i == 0);

                    if (mb_strlen($name) >= 2) {
                        $row_new[$is_simple ? 'names' : 'names_scientific'][] = $name;
                    }
                }
                $rows_new[] = $row_new;
            }
        }
        /**
         * TODO: данные из $rows_new в дальнейшем можно использовать для формирования отдельной БАЗЫ ЛАБОРАТОРНЫХ АНАЛИЗОВ
         */

        $list_symptoms = [];
        foreach ($rows_new as $row) {

            $name_bases = &$row['names'];
            $name_scientific_bases = &$row['names_scientific'];

            if (!empty($row['options'])) {
                //просто добавляем специфику симптомов, если нет локализаций

                foreach ($row['options'] as $option) {
                    $variants = [];
                    $variants_scientific = [];

                    foreach ($name_bases as &$name_base) {
                        $variants[] = $name_base . ' - ' . mb_strtolower($option);
                    }

                    foreach ($name_scientific_bases as &$name_scientific_base) {
                        $variants_scientific[] = $name_scientific_base . ' - ' . mb_strtolower($option);
                    }


                    $list_symptoms[] = [
                        'names' => implode(';', $variants),
                        'names_scientific' => implode(';', $variants_scientific),
                        'type' => 'blood_test'
                    ];
                }
            }
        }
        return $list_symptoms;
    }
}
