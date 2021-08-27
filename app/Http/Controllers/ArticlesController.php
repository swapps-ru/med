<?php

namespace App\Http\Controllers;

use App\Models\Aid;
use App\Models\AidGroup;
use App\Models\AidSubstance;
use App\Models\Article;
use App\Models\ArticleHistory;
use App\Models\Block;
use App\Models\BlockType;
use App\Models\BodySystem;
use App\Models\Disease;
use App\Models\DiseaseGroup;
use App\Models\Symptom;
use App\Models\Syndrome;
use App\Models\User;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    /**
     * TODO: удалить, когда точно будет известно, что всё ок
     */
    public function testModels()
    {
        \DB::connection()->enableQueryLog();

        $object = Aid::findOrFail(1);

        dump('Aid aid_g', $object->aidGroup()->get('*'));
        dump('Aid aid_s_i', $object->aidSubstances()->get('*'));

        dump('Aid articles', $object->articles()->get('*'));

        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////

        $object = AidSubstance::findOrFail(1);

        dump('AidSubstance restr_a_g_i/restr_a_s_i/care_a_g_i/care_a_s_i', $object->restricted_aid_group_ids, $object->restricted_aid_substance_ids, $object->careful_aid_groups_ids, $object->careful_aid_substance_ids);
        dump('AidSubstance aid_g', $object->aidGroup()->get('*'));

        dump('AidSubstance restr_aid_g', $object->restrictedAidGroups()->get('*'));
        dump('AidSubstance restr_aid_s', $object->restrictedAidSubstances()->get('*'));

        dump('AidSubstance care_aid_g', $object->carefulAidGroups()->get('*'));
        dump('AidSubstance care_aid_s', $object->carefulAidSubstances()->get('*'));

        dump('AidSubstance aids', $object->aids()->get('*'));

        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////

        $object = AidGroup::findOrFail(1);

        dump('AidGroup substances', $object->aidSubstances()->get('*'));
        dump('AidGroup aids', $object->aids()->get('*'));

        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        $object = User::findOrFail(1);

        dump('User articles', $object->articles()->get('*'));
        dump('User articles_history', $object->articlesHistory()->get('*'));


        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////

        $object = BlockType::findOrFail(1);
        dump('BlockType blocks', $object->blocks()->get('*'));

        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        $object = Block::findOrFail(1);

        dump('Block data_json', $object->data_json);

        dump('Block type', $object->blockType()->get('*'));
        dump('Block articles', $object->articles()->get('*'));

        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////

        $object = ArticleHistory::findOrFail(1);

        dump('ArticlesHistory blocks_json', $object->blocks_json);

        dump('ArticleHistory user', $object->user()->get('*'));
        dump('ArticleHistory article', $object->article()->get('*'));

        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////

        $object = Article::findOrFail(1);

        dump('Article block_i/disease_i/syndrome_i/symptom_i/aid_i', $object->block_ids, $object->disease_ids, $object->syndrome_ids, $object->symptom_ids, $object->aid_ids);

        dump('Article blocks', $object->blocks()->get('*'));
        dump('Article diseases', $object->diseases()->get('*'));
        dump('Article syndromes', $object->syndromes()->get('*'));
        dump('Article symptoms', $object->symptoms()->get('*'));
        dump('Article aids', $object->aids()->get('*'));
        dump('Article user', $object->user()->get('*'));

        dump('Article articles_h', $object->articleHistory()->get('*'));

        dump('Article symptom_main', $object->symptomMain()->get('*'));
        dump('Article disease_main', $object->diseaseMain()->get('*'));


        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////

        $object = Symptom::findOrFail(1);

        dump('Symptom names/names_s/body_s_i/options_d_j', $object->names, $object->names_scientific, $object->body_system_ids, $object->options_default_json);
        dump('Symptom body_s', $object->bodySystems()->get('*'));

        dump('Symptom syndromes', $object->syndromes()->get('*'));

        dump('Symptom article main', $object->articleMain()->get('*'));
        dump('Symptom articles', $object->articles()->get('*'));

        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////

        $object = Syndrome::findOrFail(1);

        dump('Syndrome names/names_s/symptom_i_i', $object->names, $object->names_scientific, $object->symptom_include_ids);
        dump('Syndrome articles', $object->articles()->get('*'));
        dump('Syndrome symptoms_inc', $object->symptomsInclude()->get('*'));


        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        $object = Disease::findOrFail(1);
        dump('Disease names/names_s/body_s_i', $object->names, $object->names_scientific, $object->body_system_ids);

        dump('Disease body_s', $object->bodySystems()->get('*'));
        dump('Disease groups', $object->diseaseGroups()->get('*'));
        dump('Disease complication', $object->diseaseComplication()->get('*'));
        dump('Disease article main  ', $object->articleMain()->get('*'));
        dump('Disease articles all', $object->articles()->get('*'));

        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        $object = DiseaseGroup::findOrFail(1);
        dump('DG names/names_s/body_s_i', $object->names, $object->names_scientific, $object->body_system_ids);

        dump('DG diseases', $object->diseases()->get('*'));

        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////
        $object = BodySystem::findOrFail(11);

        dump('BodySystem names', $object->names);
        $object->names = ['a', 'b'];
        dump('BodySystem names after', $object->names);

        dump('BodySystem disease_g', $object->diseaseGroups()->get('*'));
        dump('BodySystem disease_g body_s_i', $object->diseaseGroups()->get('*')->get(0)->body_system_ids);

        dump('BodySystem diseases', $object->diseases()->get('*'));

        dump($object->symptoms()->get('*'));
    }
}
