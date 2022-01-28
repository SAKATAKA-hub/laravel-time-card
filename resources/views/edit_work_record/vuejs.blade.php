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
                delete_bleak_times : [],

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
                    this.total_times = json.total_time;




                    /* --------------------------------------------------------------------------
                    | 作業用コード
                    */
                    let index = 2;
                    this.editing_index = index;
                    this.editing_work_time = Object.assign({}, this.work_times[index] );
                    this.remember_work_time = Object.assign({}, this.work_times[index] );
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
                    this.editing_work_time = Object.assign({}, this.work_times[index] );
                    this.remember_work_time = Object.assign({}, this.work_times[index] );

                    console.log(this.remember_work_time);
                },


                /*
                | 2. 勤務記録の修正中断(editCancel)
                */
                editCancel : function(){
                    this.editing_index = null;
                    this.editing_work_time = [];
                    this.remember_work_time = [];
                    this.delete_bleak_times = [];
                    this.errors = [];
                },


                /*
                | 3. 出退勤時間入力チェック(changeWorkTime)
                */
                changeWorkTime : function(){
                    const work = this.editing_work_time;
                    const breaks = work.break_times;


                    console.log({
                        in : work.input_in,
                        out : work.input_out,
                        // max_in : work.input_out.length ? work.input_out : '24:00',
                        // mmin_out : work.input_in,
                    });



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
                            this.errors = json.errors;
                        }
                        // バリデーション成功後の処理
                        else
                        {
                            console.log(json);
                            this.errors = [];
                            alert('リクエストが成功しました。');
                        }

                    })
                    .catch(error => {
                        alert('データの読み込みに失敗しました。');

                    });


                },

                /*
                | 4. 休憩時間入力チェック(changeBreakTime)
                */

                /*
                | 5. 休憩時間の削除(deleteBreakRecord)
                */

                /*
                | 6. 勤務記録の更新(updateWorkRecord)
                */

                /*
                | 7. 勤務記録の削除(deleteWorkRecord)
                */



                deleteInput : function(break_index){

                    if(break_index === null){
                        this.editing_work_time.input_out = '';
                        this.changeWorkTime(); //出退勤時間入力チェック
                    }else{
                        this.editing_work_time.break_times[break_index].input_out = '';
                        //休憩時間入力チェック(changeBreakTime)
                    }
                },



                // onSubmit: function(){



                //     // 非同期通信
                //     fetch( route.ajax_form_post, {
                //         method: 'POST',
                //         body: new URLSearchParams({
                //             _token: token,
                //             in: this.work.in,
                //             out: this.work.out,
                //         }),
                //     })
                //     .then(response => {
                //         if(!response.ok){ throw new Error(); }
                //         return response.json();
                //     })
                //     .then(json => {

                //         // バリデーション失敗の処理
                //         if(json.errors)
                //         {
                //             console.log(json);
                //             this.errors = json.errors;
                //         }
                //         // バリデーション成功後の処理
                //         else
                //         {
                //             console.log(json);
                //             this.errors = [];
                //             this.work = json.work;
                //             alert('リクエストが成功しました。');
                //         }

                //     })
                //     .catch(error => {
                //         alert('データの読み込みに失敗しました。');

                //     });

                // },
            }, //end methods
        });




    })();
</script>
