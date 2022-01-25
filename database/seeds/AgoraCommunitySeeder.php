<?php

use Illuminate\Database\Seeder;
use App\Models\AcDomain;
use App\Models\AcCategory;

use App\Models\AcContent;
use App\Models\AcVideo;
use App\Models\AcReview;
use App\Models\AcAsset;
use App\Models\AcCategoryContent;
/**
 * Class AgoraCommunitySeeder.
 */
class AgoraCommunitySeeder extends Seeder
{
    use DisableForeignKeys;


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        // DOMAIN
        AcDomain::create([
            'title' => 'Animation',
            'description' => 'Everything related to 3d animation'
        ]);

        // CATEGORIES
        AcCategory::create([
            'title' => 'Content of the day'
        ]);

        AcCategory::create([
            'title' => 'Tutorials'
        ]);

        // INITIAL CONTENT

        // VIDEO
        AcVideo::create([
            'poster' => 'pixar.jpg',
            'preview_video' => 'pixar.mp4',
            'video' => 'pixar_full.mp4'
        ]);

        AcContent::create([
            'contentable_type' => 'MorphVideo',
            'contentable_id' => 1,
            'domain_id' => 1,
            'title' => 'Pixar Internship Reel by Julian Teo',
            'description' => 'The Pixar animation reel by Julian Teo\'s Pixar Internship from 2016 is always being shown in my classes and his shot of Inside Out\'s Riley is such a good example of a strong pantomime acting performance within a set and how a set can influence your acting choices and marry both the character and the set together so that the character feels alive within that environment.',
            'slug' => 'pixar-internship-reel-by-julian-teo'
        ]);

        // CONTENT OF THE DAY
        AcCategoryContent::create([
            'ac_content_id' => 1,
            'ac_category_id' => 1
        ]);


        // ASSET
        AcAsset::create([
            'zip' => 'https://nextcloud.agora.studio/s/jTbtFya6rddtydf/download',
            'filesize' => 43764232,
            'poster' => "assets/aang/aang.jpg",
            'preview_video' => "assets/aang/aang_preview2.mp4",
            'video' => "assets/aang/aang.mp4",
        ]);

        AcContent::create([
            'contentable_type' => 'MorphAsset',
            'contentable_id' => 1,
            'domain_id' => 1,
            'title' => 'Aang',
            'description' => 'This character was created for the purpose of study, practice, animation challenges and communal content creation projects. Have fun!',
            'slug' => 'Aang'
        ]);

        // REVIEW
        AcReview::create([
            'poster' => 'pixar.jpg',
            'preview_video' => 'pixar.mp4',
            'video' => 'pixar_full.mp4',
            'syncsketch' => 'https://syncsketch.com'
        ]);

        AcContent::create([
            'contentable_type' => 'MorphReview',
            'contentable_id' => 2,
            'domain_id' => 1,
            'title' => 'Animation review',
            'description' => 'Animation review',
            'slug' => 'animationreview'
        ]);

        $this->enableForeignKeys();
    }
}

