<?php
    use App\Product;
?>

<form action="{{ url('/products/filter') }}" method="post">
    {{ csrf_field() }}
    @if(!empty($url))
        <input type="hidden" name="url" id="url" value="{{ $url }}">
    @endif
    @if(!empty($id))
        <input type="hidden" name="id" id="id" value="{{ $id }}">
    @endif
    <div class="left-sidebar">
        <h2>Category</h2>
        <div class="panel-group category-products" id="category">
            @foreach($categories as $category)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#category" href="#{{ $category->url }}">
                                <span class="badge pull-right"><i class="fa fa-plus"></i></span>
                                {{ $category->name }}
                            </a>
                        </h4>
                    </div>
                    <div id="{{ $category->url }}" class="panel-collapse collapse">
                        <div class="panel-body">
                            <ul>
                                @foreach($category->categories as $sub_category)
                                    <?php
                                        $productCount = Product::productCount($sub_category->id);
                                    ?>
                                    @if($sub_category->status == 1)
                                        <li><a href="{{ url('products/' . $sub_category->url) }}">{{ $sub_category->name }} ({{ $productCount }})</a></li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @if(!empty($url))
        <div class="left-sidebar">
            <h2>Colors</h2>
            <div class="panel-group category-products" id="color">
                @if(!empty($_GET['color']))
                    <?php
                        $colorArray = explode('-', $_GET['color']);
                    ?>
                @endif
                <div class="panel panel-default">
                    @foreach($colors as $color)
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a>
                                    {{ $color->product_color }}<span class="pull-right"><input type="checkbox" name="colorFilter[]" id="{{ $color->product_color }}" value="{{ $color->product_color }}" onchange="javascript:this.form.submit();" @if(!empty($colorArray) && in_array($color->product_color, $colorArray)) checked @endif></span>
                                </a>
                            </h4>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="left-sidebar">
            <h2>Sleeve</h2>
            <div class="panel-group category-products" id="color">
                @if(!empty($_GET['sleeve']))
                    <?php
                        $sleeveArray = explode('-', $_GET['sleeve']);
                    ?>
                @endif
                <div class="panel panel-default">
                    @foreach($sleeves as $sleeve)
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a>
                                    {{ $sleeve->description }}<span class="pull-right"><input type="checkbox" name="sleeveFilter[]" id="{{ $sleeve->description }}" value="{{ $sleeve->description }}" onchange="javascript:this.form.submit();" @if(!empty($sleeveArray) && in_array($sleeve->description, $sleeveArray)) checked @endif></span>
                                </a>
                            </h4>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="left-sidebar">
            <h2>Pattern</h2>
            <div class="panel-group category-products" id="color">
                @if(!empty($_GET['pattern']))
                    <?php
                        $patternArray = explode('-', $_GET['pattern']);
                    ?>
                @endif
                <div class="panel panel-default">
                    @foreach($patterns as $pattern)
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a>
                                    {{ $pattern->description }}<span class="pull-right"><input type="checkbox" name="patternFilter[]" id="{{ $pattern->description }}" value="{{ $pattern->description }}" onchange="javascript:this.form.submit();" @if(!empty($patternArray) && in_array($pattern->description, $patternArray)) checked @endif></span>
                                </a>
                            </h4>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</form>
