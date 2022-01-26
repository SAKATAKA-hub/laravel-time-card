<script>
    (function(){
        'use strict';

        // token
        const token = document.querySelector('meta[name="token"]').content;
        // route
        const route = {
            edit_work_record_json : document.querySelector('meta[name="route_edit_work_record_json"]').content,
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

                work_times : [],
                total_times : [],
                editing_work_time : [],
                errors : [],

            }, //end data


            mounted :function(){

                    // 勤怠一覧情報の受け取り
                    fetch( route.edit_work_record_json, {
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
                        console.log(json);
                        // JSONをdataにコピー
                        this.work_times = json.work_times;
                        this.total_times = json.total_time;

                    })
                    .catch(error => {
                        alert('データの読み込みに失敗しました。');

                    });


            }, //end mounted


            methods :{

                // 修正モーダルにデータをコピー
                editWorkTime : function(index){

                    this.editing_work_time = 'editing'+index;
                    this.editing_work_time = Object.assign({}, this.work_times[index] );
                    // console.log(this.work_times[index]);
                    console.log(this.editing_work_time);
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
