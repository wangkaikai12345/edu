<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/10/19
 * Time: 10:01
 */

/**
 * 该分类为项目初始化分类，您可以自行修改。
 * 结构：分类组 -> 分类 -> 子分类
 * 使用：database/seeds/CategoriesTableSeeder
 */
return [
    [
        'name'     => 'course',
        'children' => [
            [
                'name'     => '前沿',
                'children' => [
                    ['name' => '微服务'],
                    ['name' => '区块链'],
                    ['name' => '以太坊'],
                    ['name' => '人工智能'],
                    ['name' => '机器学习'],
                    ['name' => '深度学习'],
                    ['name' => '计算机视觉'],
                    ['name' => '数据分析'],
                    ['name' => '数据挖掘'],
                ],
            ],
            [
                'name'     => '前端',
                'children' => [
                    ['name' => 'HTML/CSS'],
                    ['name' => 'JavaScript'],
                    ['name' => 'Vue'],
                    ['name' => 'React'],
                    ['name' => 'Angular'],
                    ['name' => 'Node'],
                    ['name' => 'jQuery'],
                    ['name' => 'Bootstrap'],
                    ['name' => 'sass/less'],
                    ['name' => 'WebApp'],
                    ['name' => '前端工具'],
                    ['name' => '小程序'],
                ],
            ],
            [
                'name'     => '后端',
                'children' => [
                    ['name' => 'Java'],
                    ['name' => 'SpringBoot'],
                    ['name' => 'Python'],
                    ['name' => '爬虫'],
                    ['name' => 'Django'],
                    ['name' => 'Go'],
                    ['name' => 'PHP'],
                    ['name' => 'C'],
                    ['name' => 'C++'],
                    ['name' => 'C#'],
                    ['name' => 'Ruby'],
                ],
            ],
            [
                'name'     => '移动',
                'children' => [
                    ['name' => 'Android'],
                    ['name' => 'iOS'],
                    ['name' => 'Python'],
                    ['name' => 'React native'],
                    ['name' => 'WEEX'],
                ],
            ],
            [
                'name'     => '云计算',
                'children' => [
                    ['name' => 'Hbase'],
                    ['name' => 'Storm'],
                    ['name' => '云计算'],
                    ['name' => 'AWS'],
                    ['name' => 'Docker'],
                    ['name' => 'Kubernetes'],
                    ['name' => '算法'],
                ],
            ],
            [
                'name'     => '运维',
                'children' => [
                    ['name' => '运维'],
                    ['name' => '自动化运维'],
                    ['name' => 'Linux'],
                    ['name' => '测试'],
                    ['name' => '功能测试'],
                    ['name' => '性能测试'],
                    ['name' => '自动化测试'],
                    ['name' => '接口测试'],
                    ['name' => '安全测试'],
                ],
            ],
            [
                'name'     => '数据库',
                'children' => [
                    ['name' => 'MySQL'],
                    ['name' => 'Redis'],
                    ['name' => 'MongoDB'],
                    ['name' => 'Oracle'],
                    ['name' => 'SQL Server'],
                ],
            ],
            [
                'name'     => 'UI设计',
                'children' => [
                    ['name' => '模型制作'],
                    ['name' => '动效动画'],
                    ['name' => '设计基础'],
                    ['name' => '设计工具'],
                    ['name' => 'APPUI设计'],
                    ['name' => '产品交互'],
                ],
            ],
        ],
    ],
    [
        'name'     => 'classroom',
        'children' => [
            [
                'name'     => '职业规划',
                'children' => [
                    ['name' => 'PHP'],
                    ['name' => 'Ruby'],
                    ['name' => 'Node'],
                    ['name' => 'Python'],
                ],
            ],
        ],
    ],
];