<?php
    use App\Product;
?>

<div class="left-sidebar">
    <h2>Category</h2>
    <div class="panel-group category-products" id="accordian">
        @foreach($categories as $category)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordian" href="#{{ $category->url }}">
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
