<?php

use Illuminate\Database\Seeder;
use App\Tag;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $tags = [
        	'喜剧',
        	'恐怖',
        	'爱情',
        	'动作',
        	'科幻',
        	'武侠',
        	'战争',
        	'警匪',
        	'惊悚',
        	'悬疑',
        	'历史',
        	'儿童',
        	'家庭',
        	'言情',
        	'古装',
        	'搞笑',
        	'记录',
        	'时装'
        ];

        $i = 0;
        foreach ($tags as $tag) {
        	Tag::create([
        		'tag'=>$tag,
        		'sort'=>++$i,
        	]);
        }
    }
}
