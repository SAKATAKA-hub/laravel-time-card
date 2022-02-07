<script>
    (function(){
        'use strict';
        function showTime()
        {
            const now = new Date();
            const nowYear = now.getFullYear();
            const nowMonth = String(now.getMonth()+1).padStart(2,'0');
            const nowDate = String(now.getDate()).padStart(2,'0');
            const nowHour = String(now.getHours()).padStart(2,'0');
            const nowMin = String(now.getMinutes()).padStart(2,'0');
            const nowSec = String(now.getSeconds()).padStart(2,'0');
            const dayNum = String(now.getUTCDay());

            const DayArry =["(日)","(月)","(火)","(水)","(木)","(金)","(土)"];

            let ampm = "";
            if(nowHour<12){ampm = "AM";}
            else{ampm = "PM";}

            document.getElementById('nowDay').textContent = `${nowYear}年${nowMonth}月${nowDate}日${DayArry[dayNum]}`;
            document.getElementById('nowAmPm').textContent = ampm;
            document.getElementById('nowTime').textContent = `${nowHour % 12}:${nowMin}`;
            document.getElementById('nowSec').textContent = `:${nowSec}`;


            refresh();
        }
        function refresh(){setTimeout(showTime,1000);}
        showTime();

    })();

</script>


<script>
    (function(){
        'use strict';

        // token
        const token = document.querySelector('meta[name="token"]').content;
        // route
        const route = {
            employeees_json : document.querySelector('meta[name="route_employeees_json"]').content,
            work_in : document.querySelector('meta[name="route_work_in"]').content,
            break_in : document.querySelector('meta[name="route_break_in"]').content,
            break_out : document.querySelector('meta[name="route_break_out"]').content,
            work_out : document.querySelector('meta[name="route_work_out"]').content,
        };

        //param
        const param = {
            user_id :  document.querySelector('meta[name="user_id"]').content,
        }


        var app = new Vue({
            el : '#app',


            data : {
                form_test : false,


                employees : [],
                active_index : null,
                active_employee : [],

                workStatusText : ['退勤中','出勤中','休憩中'],

                alert_index : null,


            }, //end data




            mounted :function(){
                /*
                | 授業員情報の受け取り
                */
                fetch( route.employeees_json, {
                    method : 'POST',
                    body : new URLSearchParams({
                        _token : token,
                        user_id :  param.user_id,
                    }),
                })
                .then(response => {
                    if(!response.ok){ throw new Error(); }
                    return response.json();
                })
                .then(json => {
                    // JSONをdataにコピー
                    console.log(json);
                    this.employees = json.employees;


                    if(this.form_test){
                        //'active_employee'に選択中従業員のデータをコピーする
                        let e_index = 0;
                        this.active_employee = Object.assign({},this.employees[e_index]);
                        this.active_index = e_index;
                    }


                })
                .catch(error => {
                    alert('データの読み込みに失敗しました。');
                });
            }, //end mounted




            methods :{
                /*
                | 1. 従業員を選択(selectEmployee))
                */
                selectEmployee : function(e_index){

                    //全ての従業員の'active'を'false'にする。
                    for (let index = 0; index < this.employees.length; index++) {
                        this.employees[index].active = false;
                    }

                    //選択された従業員の'active'を'true'にする。
                    this.employees[e_index].active = true;

                    //'active_employee'に選択中従業員のデータをコピーする
                    this.active_employee = Object.assign({},this.employees[e_index]);
                    this.active_index = e_index;
                    this.alert_index = null;
                    console.log(this.active_employee);

                },


                /*
                | 2. 勤務開始(workIn)
                */
                workIn : function(){

                    fetch( route.work_in, {
                        method : 'POST',
                        body : new URLSearchParams({
                            _token : token,
                            employee_id :  this.active_employee.id,
                            work_status :  this.active_employee.work_status,
                        }),
                    })
                    .then(response => {
                        if(!response.ok){ throw new Error(); }
                        return response.json();
                    })
                    .then(json => {
                        console.log(json);
                        this.active_employee.work_status = 1 ;
                        this.employees[this.active_index].work_status = 1 ;
                        this.alert_index = 1;
                    })
                    .catch(error => {
                        alert('通信エラーが発生しました。ページを再読み込みします。');
                        location.reload();
                    });

                },


                /*
                | 3. 休憩開始(breakIn)
                */
                breakIn : function(){


                    fetch( route.break_in, {
                        method : 'POST',
                        body : new URLSearchParams({
                            _token : token,
                            employee_id :  this.active_employee.id,
                            work_status :  this.active_employee.work_status,
                        }),
                    })
                    .then(response => {
                        if(!response.ok){ throw new Error(); }
                        return response.json();
                    })
                    .then(json => {
                        console.log(json);
                        this.active_employee.work_status = 2 ;
                        this.employees[this.active_index].work_status = 2 ;
                        this.alert_index = null;
                    })
                    .catch(error => {
                        alert('通信エラーが発生しました。ページを再読み込みします。');
                        location.reload();
                    });

                },


                /*
                | 4. 休憩終了(breakOut)
                */
                breakOut : function(){

                    fetch( route.break_out, {
                        method : 'POST',
                        body : new URLSearchParams({
                            _token : token,
                            _method : 'PATCH',
                            employee_id :  this.active_employee.id,
                            work_status :  this.active_employee.work_status,
                        }),
                    })
                    .then(response => {
                        if(!response.ok){ throw new Error(); }
                        return response.json();
                    })
                    .then(json => {
                        console.log(json);
                        this.active_employee.work_status = 1 ;
                        this.employees[this.active_index].work_status = 1 ;
                        this.alert_index = 2;
                    })
                    .catch(error => {
                        alert('通信エラーが発生しました。ページを再読み込みします。');
                        // location.reload();
                    });

                },


                /*
                | 5. 勤務終了(workOut)
                */
                workOut : function(){

                    fetch( route.work_out, {
                        method : 'POST',
                        body : new URLSearchParams({
                            _token : token,
                            _method : 'PATCH',
                            employee_id :  this.active_employee.id,
                            work_status :  this.active_employee.work_status,
                        }),
                    })
                    .then(response => {
                        if(!response.ok){ throw new Error(); }
                        return response.json();
                    })
                    .then(json => {
                        console.log(json);
                        this.active_employee.work_status = 0 ;
                        this.employees[this.active_index].work_status = 0 ;
                        this.alert_index = 0;
                    })
                    .catch(error => {
                        alert('通信エラーが発生しました。ページを再読み込みします。');
                        // location.reload();
                    });

                },


                // /*
                // | 3. 出退勤時間入力チェック(changeWorkTime)
                // */
                // changeWorkTime : function(){
                //     // エラーのリセット
                //     this.errors = [];

                //     // 非同期通信
                //     fetch( route.validate_input_time, {
                //         method: 'POST',
                //         body: new URLSearchParams({
                //             _token: token,
                //             work_time : JSON.stringify(this.editing_work_time),
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
                //             // エラー内容の保存
                //             this.errors = json.errors;
                //             // 編集内容をエラー前に戻す
                //             this.editing_work_time = this.assign_time(this.remember_work_time);
                //         }
                //         // バリデーション成功後の処理
                //         else
                //         {
                //             console.log(json);
                //             this.errors = [];
                //             this.remember_work_time = this.assign_time(this.editing_work_time);
                //         }

                //     })
                //     .catch(error => {
                //         // 編集内容をエラー前に戻す
                //         this.editing_work_time = this.assign_time(this.remember_work_time);
                //         alert('データの読み込みに失敗しました。');
                //     });


                // },

                // /*
                // | 4. 休憩時間の削除(deleteBreakRecord)
                // */
                // deleteBreakRecord : function(b_index){

                //     console.log(b_index+'休憩時間の削除');
                //     const break_times = this.editing_work_time.break_times;

                //     const b_array = [];
                //     for (let index = 0; index < Object.keys(break_times).length; index++) {

                //         if(index == b_index){
                //             this.delete_break_times.push(break_times[index]);
                //         }else{
                //             b_array.push(break_times[index]);
                //         }
                //     }

                //     this.editing_work_time.break_times = b_array;
                // },

                // /*
                // | 5. 勤務記録の更新(updateWorkRecord)
                // */
                // updateWorkRecord : function(){

                //     // 非同期通信
                //     fetch( route.update, {
                //         method: 'POST',
                //         body: new URLSearchParams({
                //             _token: token,
                //             _method : 'PATCH',
                //             work_time : JSON.stringify(this.editing_work_time),
                //             delete_break_times : JSON.stringify(this.delete_break_times),
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
                //             // エラー内容の保存
                //             this.errors = json.errors;
                //             // 編集内容をエラー前に戻す
                //             this.editing_work_time = this.assign_time(this.remember_work_time);

                //         }
                //         // バリデーション成功後の処理
                //         else
                //         {
                //             console.log(json);
                //             // this.errors = [];
                //             // this.remember_work_time = this.assign_time(this.editing_work_time);
                //             this.work_times = json[0].work_times;
                //             this.total_times = json[0].total_times;

                //             this.editCancel(); //勤怠編集モーダル関係データのリセット

                //             alert('勤怠情報を更新しました。');
                //         }

                //     })
                //     .catch(error => {
                //         alert('通信エラーが発生しました。ページを再読み込みします。');
                //         location.reload();

                //     });

                // },

                // /*
                // | 6. 勤務記録の削除(deleteWorkRecord)
                // */
                // deleteWorkRecord : function(){


                //     // 非同期通信
                //     fetch( route.destroy, {
                //         method: 'POST',
                //         body: new URLSearchParams({
                //             _token: token,
                //             _method : 'DELETE',
                //             work_time : JSON.stringify(this.editing_work_time),
                //         }),
                //     })
                //     .then(response => {
                //         if(!response.ok){ throw new Error(); }
                //         return response.json();
                //     })
                //     .then(json => {
                //         console.log(json);
                //         this.errors = [];

                //         this.work_times = json[0].work_times;
                //         this.total_times = json[0].total_times;

                //         this.editCancel(); //勤怠編集モーダル関係データのリセット

                //         alert('勤怠情報を更新しました。');
                //     })
                //     .catch(error => {
                //         alert('データの読み込みに失敗しました。');

                //     });

                // },



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


            }, //end methods


        }); //end var app


    })();
</script>
