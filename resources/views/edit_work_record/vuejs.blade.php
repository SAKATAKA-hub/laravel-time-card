<script>
    (function(){
        'use strict';

        // token
        const token = document.querySelector('meta[name="token"]').content;
        // route
        const route = {
            records_json : document.querySelector('meta[name="route_records_json"]').content,
            validate_input_time : document.querySelector('meta[name="route_validate_input_time"]').content,

        };
        //param
        const param = {
            user_id :  document.querySelector('meta[name="user_id"]').content,
            date :  document.querySelector('meta[name="date"]').content,
        }


        var app = new Vue({
            el : '#app',


            data : {
                form_test : true,

                // 表示データ
                work_times : [],
                total_times : [],
                errors : [],

                // 編集データ
                editing_index : null,
                editing_work_time : [],
                remember_work_time : [],
                delete_break_times : [],

            }, //end data




            mounted :function(){
                /*
                | 勤怠一覧情報の受け取り
                */
                fetch( route.records_json, {
                    method : 'POST',
                    body : new URLSearchParams({
                        _token : token,
                        user_id :  param.user_id,
                        date :  param.date,
                    }),
                })
                .then(response => {
                    if(!response.ok){ throw new Error(); }
                    return response.json();
                })
                .then(json => {
                    // JSONをdataにコピー
                    console.log(json);
                    this.work_times = json.work_times;
                    this.total_times = json.total_times;

                    /* --------------------------------------------------------------------------
                    | 作業用コード
                    */
                    if(this.form_test){
                        let index = 2;
                        this.editing_index = index;

                        let assign_ob = this.work_times[index];
                        this.editing_work_time = this.assign_time(assign_ob);
                        this.remember_work_time = this.assign_time(assign_ob);

                    }
                    //----------------------------------------------------------------------------

                })
                .catch(error => {
                    alert('データの読み込みに失敗しました。');

                });


            }, //end mounted




            methods :{
                /*
                | 1. 勤務記録の修正開始(editStart)
                */
                editStart : function(index){
                    this.editing_index = index;
                    let assign_ob = this.work_times[index];
                    this.editing_work_time = this.assign_time(assign_ob);
                    this.remember_work_time = this.assign_time(assign_ob);
                },


                /*
                | 2. 勤務記録の修正中断(editCancel)
                */
                editCancel : function(){
                    this.editing_index = null;
                    this.editing_work_time = [];
                    this.remember_work_time = [];
                    this.delete_break_times = [];
                    this.errors = [];
                },


                /*
                | 3. 出退勤時間入力チェック(changeWorkTime)
                */
                changeWorkTime : function(){
                    // エラーのリセット
                    this.errors = [];

                    // 非同期通信
                    fetch( route.validate_input_time, {
                        method: 'POST',
                        body: new URLSearchParams({
                            _token: token,
                            work_time : JSON.stringify(this.editing_work_time),
                        }),
                    })
                    .then(response => {
                        if(!response.ok){ throw new Error(); }
                        return response.json();
                    })
                    .then(json => {

                        // バリデーション失敗の処理
                        if(json.errors)
                        {
                            console.log(json);
                            // エラー内容の保存
                            this.errors = json.errors;
                            // 編集内容をエラー前に戻す
                            this.editing_work_time = this.assign_time(this.remember_work_time);

                            console.log(this.errors.valiWorkTime_in);
                        }
                        // バリデーション成功後の処理
                        else
                        {
                            console.log(json);
                            this.errors = [];
                            this.remember_work_time = this.assign_time(this.editing_work_time);

                            alert('リクエストが成功しました。');
                        }

                    })
                    .catch(error => {
                        alert('データの読み込みに失敗しました。');

                    });


                },

                /*
                | 4. 休憩時間の削除(deleteBreakRecord)
                */
                deleteBreakRecord : function(b_index){

                    console.log(b_index+'休憩時間の削除');
                },

                /*
                | 5. 勤務記録の更新(updateWorkRecord)
                */
                updateWorkRecord : function(){

                    console.log(this.editing_index+'勤務時間の更新');
                },

                /*
                | 6. 勤務記録の削除(deleteWorkRecord)
                */
                deleteWorkRecord : function(){

                    console.log(this.editing_index+'勤務時間の削除');
                },



                /*
                |------------------------------------------
                | メソッド内で利用するメソッド
                |------------------------------------------
                */

                /*
                 * M-1 勤務時間オブジェクトのコピーを返す
                 *
                 * @Param assign_ob (コピー元のオブジェクト)
                */
                assign_time : function(assign_ob){

                    // 勤務時間のコピー
                    const work_time = Object.assign({},assign_ob);

                    // 休憩時間のコピー
                    const assign_break_times = assign_ob.break_times;
                    const break_times = [];
                    for (let b_index = 0; b_index < Object.keys(assign_break_times).length; b_index++) {
                        break_times[b_index] = Object.assign( {}, assign_break_times[b_index] );
                    }

                    // 休憩時間オブジェクトを勤務時間オブジェクトへ保存
                    work_time.break_times = Object.assign( {},break_times );

                    return work_time;
                },


                deleteInput : function(break_index){

                    if(break_index === null){
                        this.editing_work_time.input_out = '';
                        this.changeWorkTime(); //出退勤時間入力チェック
                    }else{
                        this.editing_work_time.break_times[break_index].input_out = '';
                        //休憩時間入力チェック(changeBreakTime)
                    }
                },



            }, //end methods
        });




    })();
</script>
