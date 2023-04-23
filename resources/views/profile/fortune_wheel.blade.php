@extends('layouts.profile')

@section('in_content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <div class="row page-titles">
                <div class="col-md-12 col-12 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Выиграи 100$ БЕСПЛАТНО</h3>
                </div>

                <div class="col-md-6 col-4 align-self-center">
                    <div id="chart"></div>
                    <div id="question"><h1></h1></div>
                </div>
            </div>
            <hr>

            <div class="col-md-12 col-12 align-self-center m-t-15">
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                    <h3 class="text-success"><i class="fa fa-check-circle"></i><b> Кто может участвовать? </b><br>
                        Партнеры с личным ТО 800 BM - 1 раз может, 1400- 2 раза, 2000- 3 раз. Подписка на первую линию - 1 раз может
                    </h3>
                </div>
            </div>

            <div class="row page-titles">

                <div class="col-md-12 col-12 align-self-center">

                    <div class="table-responsive">
                        <table id="demo-foo-addrow" class="table table-hover no-wrap contact-list" data-page-size="10">
                            <thead>
                            <tr>
                                <th>ID #</th>
                                <th>Статус</th>
                                <th>Партнер</th>
                                <th>Дата</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $item)

                                <tr @if($item->status == 1) style="color: #5cb85c" @else style="color: #f62d51" @endif>
                                    <td class="text-center">{{ $item->id }}</td>
                                    <td class="text-center">@if($item->status == 1) Выграно @else Не выиграно @endif</td>
                                    <td class="txt-oflo">{{ \App\User::find($item->user_id)->name }}</td>
                                    <td class="txt-oflo">{{ $item->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        @include('layouts.footer')
        <!-- ============================================================== -->
    </div>
@endsection

@section('body-class')
    fix-header card-no-border fix-sidebar
@endsection

@push('styles')
    <style type="text/css">
        text{
            font-family:Helvetica, Arial, sans-serif;
            font-size:11px;
            pointer-events:none;
        }
        #chart{
            /*position:absolute;*/
            width:500px;
            height:500px;
            top:0;
            left:0;
        }
        #question{
            position: absolute;
            width:400px;
            height:500px;
            top:0;
            left:520px;
        }
        #question h1{
            font-size: 50px;
            font-weight: bold;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            position: absolute;
            padding: 0;
            margin: 0;
            top:50%;
            -webkit-transform:translate(0,-50%);
            transform:translate(0,-50%);
            color: #56cd63;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        var padding = {top:20, right:40, bottom:0, left:0},
            w = 500 - padding.left - padding.right,
            h = 500 - padding.top  - padding.bottom,
            r = Math.min(w, h)/2,
            rotation = 0,
            oldrotation = 0,
            picked = 100000,
            oldpick = [],
            color = d3.scale.category20();//category20c()
        //randomNumbers = getRandomNumbers();

        //http://osric.com/bingo-card-generator/?title=HTML+and+CSS+BINGO!&words=padding%2Cfont-family%2Ccolor%2Cfont-weight%2Cfont-size%2Cbackground-color%2Cnesting%2Cbottom%2Csans-serif%2Cperiod%2Cpound+sign%2C%EF%B9%A4body%EF%B9%A5%2C%EF%B9%A4ul%EF%B9%A5%2C%EF%B9%A4h1%EF%B9%A5%2Cmargin%2C%3C++%3E%2C{+}%2C%EF%B9%A4p%EF%B9%A5%2C%EF%B9%A4!DOCTYPE+html%EF%B9%A5%2C%EF%B9%A4head%EF%B9%A5%2Ccolon%2C%EF%B9%A4style%EF%B9%A5%2C.html%2CHTML%2CCSS%2CJavaScript%2Cborder&freespace=true&freespaceValue=Web+Design+Master&freespaceRandom=false&width=5&height=5&number=35#results

        var data = [
            {"label":"Блок 1",  "value":1,  "question":"Пусть твоя радость будет бесконечной!"}, // padding
            {"label":"Блок 2",  "value":2,  "question":"Желаю твоему настроению оставаться удивительно бодрым!"}, //font-family
            {"label":"Блок 3",  "value":3,  "question":"Пусть каждый миг несет тебе счастье!"}, //color
            {"label":"Блок 4",  "value":4,  "question":"Желаю букетов, признаний,"}, //font-weight
            {"label":"Блок 5",  "value":5,  "question":"Не знать никогда расставаний!"}, //font-size
            {"label":"Блок 6",  "value":6,  "question":"Желаю, чтобы каждый новый день начинался с лучезарной улыбки!"}, //background-color
            {"label":"Блок 7",  "value":7,  "question":"Поздравляю, вы выиграли 100$"}, //nesting
            {"label":"Блок 8",  "value":8,  "question":"Пускай на душе царят благодать и покой!"}, //bottom
            {"label":"Блок 9",  "value":9,  "question":"Пусть хандра и тревоги обходят твой дом за версту!"}, //sans-serif
            {"label":"Блок 10", "value":10, "question":"Желаю не торопить жизнь и наслаждаться каждым ее мгновением!"}, //period
            {"label":"Блок 11", "value":11, "question":"В любой ситуации ищи лишь позитивные и добрые моменты!"}, //pound sign
            {"label":"Блок 12", "value":12, "question":"Никогда не теряй оптимизма, желаю только положительных эмоций!"}, //<body>
            {"label":"Блок 13", "value":13, "question":"Не ведай тревог и горечи, будь на позитиве!"}, //<ul>
            {"label":"Блок 14", "value":14, "question":"Ощути мой бодрящий привет, настройся на радостную волну!"}, //<h1>
            {"label":"Блок 15", "value":15, "question":"Достигай желаемого!"}, //margin
            {"label":"Блок 16", "value":16, "question":"Желаю жизнелюбия!"}, //< >
            {"label":"Блок 17", "value":17, "question":"Пусть у тебя будет много радостных, волнительных праздников и ни капли разочарований!"}, // { }
            {"label":"Блок 18", "value":18, "question":"Желаю хорошему настроению никогда не покидать тебя!"}, //<p>
            {"label":"Блок 19", "value":19, "question":"Пусть мечты станут реальностью, успехи удивят своими размерами, а возможности не узнают границ!"}, //<!DOCTYPE html>
            {"label":"Блок 20", "value":20, "question":"Пусть в сердце живет чудесная любовь, а душа наполняется светом счастья!"}, //<head>
            {"label":"Блок 21", "value":21, "question":"Пусть твоя радость будет бесконечной!"}, // colon
            {"label":"Блок 22", "value":22, "question":"Желаю твоему настроению оставаться удивительно бодрым!"}, // <style>
            {"label":"Блок 23", "value":23, "question":"Пусть каждый миг несет тебе счастье!"}, // .html
            {"label":"Блок 24", "value":24, "question":"Желаю, чтобы каждый новый день начинался с лучезарной улыбки!"}, // HTML
            {"label":"Блок 25", "value":25, "question":"Пускай на душе царят благодать и покой!"}, // CSS
            {"label":"Блок 26", "value":26, "question":"Пусть хандра и тревоги обходят твой дом за версту!"}, // JavaScript
            {"label":"Блок 27", "value":27, "question":"Желаю не торопить жизнь и наслаждаться каждым ее мгновением!"}, // border
            {"label":"Блок 28", "value":28, "question":"В любой ситуации ищи лишь позитивные и добрые моменты!"},//semi-colon
            {"label":"Блок 29", "value":29, "question":"Ощути мой бодрящий привет, настройся на радостную волну!"}, //100%
            {"label":"Блок 30", "value":30, "question":"Никогда не теряй оптимизма, желаю только положительных эмоций!"} //comma
        ];


        var svg = d3.select('#chart')
            .append("svg")
            .data([data])
            .attr("width",  w + padding.left + padding.right)
            .attr("height", h + padding.top + padding.bottom);

        var container = svg.append("g")
            .attr("class", "chartholder")
            .attr("transform", "translate(" + (w/2 + padding.left) + "," + (h/2 + padding.top) + ")");

        var vis = container
            .append("g");

        var pie = d3.layout.pie().sort(null).value(function(d){return 1;});

        // declare an arc generator function
        var arc = d3.svg.arc().outerRadius(r);

        // select paths, use arc generator to draw
        var arcs = vis.selectAll("g.slice")
            .data(pie)
            .enter()
            .append("g")
            .attr("class", "slice");


        arcs.append("path")
            .attr("fill", function(d, i){ return color(i); })
            .attr("d", function (d) { return arc(d); });

        // add the text
        arcs.append("text").attr("transform", function(d){
            d.innerRadius = 0;
            d.outerRadius = r;
            d.angle = (d.startAngle + d.endAngle)/2;
            return "rotate(" + (d.angle * 180 / Math.PI - 90) + ")translate(" + (d.outerRadius -10) +")";
        })
            .attr("text-anchor", "end")
            .text( function(d, i) {
                return data[i].label;
            });

        container.on("click", spin);


        function spin(d){

            var attempt = 0;

            $.ajax({
                url: "/fortune_wheel_access/{{ \Illuminate\Support\Facades\Auth::user()->id }}",
                type: 'GET',
                dataType: 'json', // added data type
                success: function(res) {
                    console.log(res);

                    if(res > 0){
                        container.on("click", null);

                        //all slices have been seen, all done
                        console.log("OldPick: " + oldpick.length, "Data length: " + data.length);
                        if(oldpick.length == data.length){
                            console.log("done");
                            container.on("click", null);
                            return;
                        }

                        var  ps       = 360/data.length,
                            pieslice = Math.round(1440/data.length),
                            rng      = Math.floor((Math.random() * 1440) + 360);

                        rotation = (Math.round(rng / ps) * ps);

                        picked = Math.round(data.length - (rotation % 360)/ps);
                        picked = picked >= data.length ? (picked % data.length) : picked;


                        if(oldpick.indexOf(picked) !== -1){
                            d3.select(this).call(spin);
                            return;
                        } else {
                            oldpick.push(picked);
                        }

                        rotation += 90 - Math.round(ps/2);

                        vis.transition()
                            .duration(3000)
                            .attrTween("transform", rotTween)
                            .each("end", function(){

                                //mark question as seen
                                d3.select(".slice:nth-child(" + (picked + 1) + ") path")
                                    .attr("fill", "#111");

                                //populate question
                                d3.select("#question h1")
                                    .text(data[picked].question);

                                $.ajax({
                                    url: "/fortune_wheel_attempt/{{ \Illuminate\Support\Facades\Auth::user()->id }}/" + data[picked].value,
                                    type: 'GET',
                                    dataType: 'json', // added data type
                                });

                                oldrotation = rotation;

                                container.on("click", spin);
                            });
                    }
                    else{
                        alert("У вас не осталось или попытки, попробуйте в следующем месяце");
                    }
                }
            });
        }

        //make arrow
        svg.append("g")
            .attr("transform", "translate(" + (w + padding.left + padding.right) + "," + ((h/2)+padding.top) + ")")
            .append("path")
            .attr("d", "M-" + (r*.15) + ",0L0," + (r*.05) + "L0,-" + (r*.05) + "Z")
            .style({"fill":"#56cd63"});

        //draw spin circle
        container.append("circle")
            .attr("cx", 0)
            .attr("cy", 0)
            .attr("r", 60)
            .style({"fill":"white","cursor":"pointer"});

        //spin text
        container.append("text")
            .attr("x", 0)
            .attr("y", 15)
            .attr("text-anchor", "middle")
            .text("КРУТИ")
            .style({"font-weight":"bold", "color":"#56cd63", "font-size":"30px"});


        function rotTween(to) {
            var i = d3.interpolate(oldrotation % 360, rotation);
            return function(t) {
                return "rotate(" + i(t) + ")";
            };
        }


        function getRandomNumbers(){
            var array = new Uint16Array(1000);
            var scale = d3.scale.linear().range([360, 1440]).domain([0, 100000]);

            if(window.hasOwnProperty("crypto") && typeof window.crypto.getRandomValues === "function"){
                window.crypto.getRandomValues(array);
                console.log("works");
            } else {
                //no support for crypto, get crappy random numbers
                for(var i=0; i < 1000; i++){
                    array[i] = Math.floor(Math.random() * 100000) + 1;
                }
            }

            return array;
        }

    </script>
@endpush
