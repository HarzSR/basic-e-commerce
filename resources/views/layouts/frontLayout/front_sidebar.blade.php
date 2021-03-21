<div class="left-sidebar">
    <h2>Category</h2>
    <div class="panel-group category-products" id="accordian"><!--category-productsr-->
        <div class="panel panel-default">
            <?php // echo $category_menu; ?>
            @foreach($categories as $category)
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#{{ $category->id }}" href="#{{ $category->url }}">
                            <span class="badge pull-right"><i class="fa fa-plus"></i></span>
                            {{ $category->name }}
                        </a>
                    </h4>
                </div>
                <div id="{{ $category->url }}" class="panel-collapse collapse">
                    <div class="panel-body">
                        <ul>
                            @foreach($category->categories as $sub_category)
                                <li><a href="{{ url('products/' . $sub_category->url) }}">{{ $sub_category->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </div><!--/category-products-->

</div>
