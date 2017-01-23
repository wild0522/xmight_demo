@extends('layouts.app')

@section('content')
    <script type="text/javascript">
        google.charts.load('current', {packages: ['corechart', 'line']});
        google.charts.setOnLoadCallback(function () {
            options = {
                crosshair: {
                    color: '#000',
                    trigger: 'selection'
                }
            };

            chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        });
        
        var chartData, options, chart = null;
        
    </script>
    
    
<div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Console</div>

                    <div class="panel-body">
                        <div class="row">
                            <form id="add_form" method="POST" accept-charset="UTF-8" action="{{url('channel')}}">
                            {{csrf_field()}}
                            <div class="col-sm-8" ><input name="name" type="text" class="form-control" value="" placeholder="Enter Channel name like: ch1, ch2"></div>
                            <div class="col-sm-4" ><button type="button" id="add" class="btn btn-block">Add Channel</button></div>
                            {{ Form::close() }}
                        </div>
                        <br />
                        <label for="usr">Channel list</label><br />
                            @forelse ($channels as $ch)
                        <div class="row channel_row" chid="{{$ch['id']}}" style="padding-bottom: 5px;">
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="name" value="{{ $ch['name'] }}">
                            </div>
                            <div class="col-sm-5">
                                <button class="btn btn-default do_random">Random</button>
                                <button class="btn btn-info do_patch">Change</button>
                                <button class="btn btn-warning do_del">Delete</button>
                                {{--<button class="btn btn-success do_item">Item</button>--}}
                            </div>
                        </div>
                            @empty
                                <div class="row">
                                    <p class="col-lg-12">No Channel!</p>
                                </div>
                            @endforelse
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Chart</div>
                    <div class="panel-body">
                        <div id="chart_div"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    $('#add_form input').keypress(function (e) {
        if (e.which == 13) {
            $('#add').trigger('click');
            return false;
        }
    });
    
    $('#add').click(function (e) {
        $.ajax({
            type: $("#add_form").attr('method'),
            data: $("#add_form").serialize(),
            url: $("#add_form").attr('action'),
            success: function(data)
            {
                if(data.status === 200){
                    location.reload();
                }else{
                    alert(data.msg);
                }
            }
        });
    });
    var tasks = [], sync_tasks = [], items_data = [], line_seq = [];
    $('.channel_row').on('click', 'button', function () {
        var root = $(this).parents('.channel_row').eq(0);
        var chid = root.attr('chid');
        var ch_row = $(this);
        
        if($(this).hasClass('do_patch')){
            $.ajax({
                type: 'patch',
                dataType: 'json',
                data: {name:$(root).find('input[name=name]').val(), '_token':'{{csrf_token()}}'},
                url: '{{url('channel')}}/' + chid,
                success: function(data)
                {
                    if(data.status === 200){
                        alert('success');
                    }else{
                        alert(data.msg);
                    }
                }
            });
        }else if($(this).hasClass('do_del')){
            $.ajax({
                type: 'delete',
                dataType: 'json',
                data: {'_token':'{{csrf_token()}}'},
                url: '{{url('channel')}}/' + chid,
                success: function(data)
                {
                    if(data.status === 200){
                        $(root).remove();
                    }else{
                        alert(data.msg);
                    }
                }
            });
        }else if($(this).hasClass('do_item')){
            $.ajax({
                type: 'post',
                dataType: 'json',
                data: {'_token':'{{csrf_token()}}'},
                url: '{{url('channel')}}/' + chid + '/item',
                success: function(data)
                {
                    if(data.status === 200){
                    }else{
                        alert(data.msg);
                    }
                }
            });
        }else if($(this).hasClass('do_random')){
            if($(this).hasClass('btn-success')){
                $(ch_row).clearQueue().stop();
                
                clearInterval(tasks[chid]);
                clearInterval(sync_tasks[chid]);
                
                var idx = line_seq.indexOf(chid);
                if (idx > -1) {
                    line_seq.splice(idx, 1);
                }
                if(line_seq.length === 0){
                    $('#chart_div').hide();
                }
                
                $(this).removeClass('btn-success');
            }else{
                tasks[chid] = setInterval(function(){
                    $(ch_row).queue(function(){
                        randItem(chid, ch_row);
                    });
                }, ((Math.floor(Math.random() * 3) + 1)*0.5 + 0.5)*1000);
                sync_tasks[chid] = setInterval(function(){
                    $(ch_row).queue(function(){
                        syncItem(chid, ch_row);
                    });
                }, 1000);
                
                line_seq.push(chid);
                $('#chart_div').show();
                
                $(this).addClass('btn-success');
            }
            
        }
    });

    
    var randItem = function (chid, obj) {
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {'_token':'{{csrf_token()}}'},
            url: '{{url('channel')}}/' + chid + '/item',
            success: function(data)
            {
                if(data.status === 200){
                }else{
                    console.log(data.msg);
                }
                    $(obj).dequeue();
            }
        });
    };

    var syncItem = function (chid, obj) {
        $.ajax({
            type: 'get',
            dataType: 'json',
            data: {'_token':'{{csrf_token()}}'},
            url: '{{url('channel')}}/' + chid + '/0/0',
            success: function(data)
            {
                if(data.status === 200){
                    var len = data.data.length - 50 > 0 ? data.data.length - 50 : 0;
                    items_data[chid] = data.data.slice(len);
                }else{
                    console.log(data.msg);
                }
                $(obj).dequeue();
            }
        });
    };
    
    var rebuild = function() {
        if(line_seq.length === 0){
            return true;
        }
        
        var _items_data = items_data;
        var map = [];
        var map_sort = [];
        $.each(_items_data, function (chid, fvalue) {
            $.each(fvalue, function (k, v) {
                if( typeof map['at_' + v['created_at']] !== 'array'){
                    map['_' + v['created_at']] = new Array;
                }
                map['_' + v['created_at']][chid] = v['value'];
                map_sort.push(v['created_at']);
            });
        });
        map_sort = $.unique(map_sort.sort());
        _items_data = null;
        
        var rows = [];
        var prev_value = [];
        $.each(map_sort, function (k, created_at) {
            var ch = map['_' + created_at];
            var el = [];
            el.push(new Date(created_at * 1000));
            $.each(line_seq, function (k, chid) {
                if(typeof ch[chid] !== 'undefined'){
                    el.push(parseInt(ch[chid]));
                    prev_value[chid] = ch[chid];
                }else{
                    el.push(parseInt(prev_value[chid]));
                }
            });
            
            rows.push(el);
        });
                
        var len = rows.length - 50 >= 0 ? rows.length -50 : 0;
        rows = rows.slice(len);
        //rebuild chart
        chartData = null;
        chartData = new google.visualization.DataTable();

        chartData.addColumn('datetime', 'Time of Day');
        $.each(line_seq, function (k, chid) {
            chartData.addColumn('number', $('.channel_row[chid='+chid+']').find('input[name=name]').val());
        });
        chartData.addRows(rows);
        chart.draw(chartData, options);
        
    };
    setInterval(function(){
        rebuild();
    }, 1000);
    
</script>    
@endsection