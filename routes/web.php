<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\GitController;
use morphos\Russian\NounDeclension;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/article-constructor', function () {
    return Inertia::render('ArticleConstructor');
});


Route::get('/parse', function () {
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
        $string_imploded = implode(', ', array_merge($name_bases, $name_scientific_bases));
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
            'names' => implode(', ', $variants),
            'names_scientific' => implode(', ', $variants_scientific),
            'type' => $type
        ];


        if (!empty($row['locations'])) {
            //если есть локализация делаем набор сочетаний основного симптома и локализаций

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
                            'names' => implode(', ', $variants),
                            'names_scientific' => implode(', ', $variants_scientific),
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
                        'names' => implode(', ', $variants),
                        'names_scientific' => implode(', ', $variants_scientific),
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
                    'names' => implode(', ', $variants),
                    'names_scientific' => implode(', ', $variants_scientific),
                    'type' => $type
                ];
            }
        }
    }
    dump($list_symptoms);
    foreach ($list_symptoms as $row) {
        if (mb_strlen($row['names']) > 255 || mb_strlen($row['names']) > 255) {
            dump($row);
        }
    }
    //return $list_symptoms;
});


Route::any(env('PATH_ADMIN_NAME') . '/git-auto', [GitController::class, 'gitPull'])->name('admin.git-pull');


require __DIR__ . '/auth.php';
