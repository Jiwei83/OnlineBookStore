<?php
/**
 * Created by PhpStorm.
 * User: majiwei
 * Date: 26/08/2017
 * Time: 2:42 PM
 */
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class StudentController extends  Controller
{
    public function test1()
    {
        //select
//        $students = DB::select('select * from student');
//        dd($students);

        //insert
//        $bool = DB::insert('insert into student(name, age) values(?, ?)',
//            ['Ma', 19]);
//        var_dump($bool);

        //update
//        $num = DB::update('update student set age = ? where name = ?',
//            [20, 'Kric']);
//        var_dump($num);

        //delete
        $num = DB::delete('delete from student where id > ?', [1001]);
        var_dump($num);
    }

    //使用查询构造器添加数据
    public function query1()
    {

//        $bool = DB::table('student')->insert(['name' => 'immoc', 'age' => 18]);
//        var_dump($bool);

        //获取插入数据的id
//        $num = DB::table('student')->insertGetId(['name' => 'test', 'age' => 28]);
//        var_dump($num);

        //插入多条数据
        $bool = DB::table('student')->insert(
            [['name' => 'hahah', 'age' => 20],
                ['name' => 'lol', 'age' => 18]]
        );
        var_dump($bool);
    }

    //使用查询构造器更新数据
    public function query2()
    {
        //update
        //DB::table('student')->where('id', 1003)->update(['age' => 30]);

        //属性自增1
        //DB::table('student')->increment('age');

        //属性自增多
        //DB::table('student')->increment('age', 3);

        //属性自减
        //DB::table('student')->decrement('age');

        //属性变化条件设定
        $num = DB::table('student')->where('id', 1001)->increment('age');
        var_dump($num);
    }

    //使用查询构造器删除数据
    public function query3()
    {
//        $num = DB::table('student')->where('id', 1001)->delete();
//        var_dump($num);
        $num = DB::table('student')->where('id', '>=', 1007)->delete();
        var_dump($num);
    }

    //
    public function query4()
    {
//        DB::table('student')->insert([
//            ['name' => 'lol', 'age' => 19],
//            ['name' => 'test', 'age' => 30]
//        ]);
//        $students = DB::table('student')->get();
//        dd($students);

        //first获取第一条数据
//        $students = DB::table('student')->orderBy('age', 'desc')->first();
//        dd($students);

        //where多个条件
//        $students = DB::table('student')
//            ->whereRaw('age >= ? and id >= ?', [20, 1002])->get();
//        dd($students);

        //pluck返回结果集中字段
//        $students = DB::table('student')
//            ->pluck('name');
//        dd($students);

        //select选择字段
//        $names = DB::table('student')
//            ->select('id', 'name', 'age')
//            ->get();
//        dd($names);

        //chunk分批查询必须添加orderby
        echo "<pre>";
        DB::table('student')->orderBy('id','desc')->chunk(2, function($student){
            var_dump($student);
        });

    }

    public function query() {

    }


}