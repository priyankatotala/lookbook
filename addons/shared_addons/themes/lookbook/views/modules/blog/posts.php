<div class="row">
    <div class="col-sm-9">
        {{ if posts }}

        {{ posts }}

        <div id="{{ slug }}" class="post">
            <header>
                <h1 id="{{ slug }}">{{ title }}</h1>                        
            </header>

            <div class="meta">

                {{ if category }}
                <!--<div class="category">
                        {{ helper:lang line="blog:category_label" }}
                        <span><a href="blog/category/{{ category:slug }}">{{ category:title }}</a></span>
                </div>-->
                {{ endif }}

                {{ if keywords }}
                <div class="keywords">
                    {{ keywords }}
                    <span><a href="blog/tagged/{{ keyword }}">{{ keyword }}</a></span>
                    {{ /keywords }}
                </div>
                {{ endif }}

            </div>

            <div class="post_body preview">
                {{ body }}
            </div>

                        <!--<p><a href="{{ url }}" class="btn btn-success">{{ helper:lang line="blog:read_more_label" }}</a></p>-->

        </div>

        {{ /posts }}

        {{ pagination }}

        {{ else }}

        {{ helper:lang line="blog:currently_no_posts" }}

        {{ endif }}
    </div>
    <div class="col-sm-3">
        <ul id='sidebar' class="list-group" style="margin-top:120px;">
        {{ navigation:links group="sidebar" }}
        <li class="list-group-item"><a href="{{ url }}" class="{{ class }}">{{ title }}</a>
            {{ if children }}
            <ul>
            {{ children }}
                <li><a href="{{ url }}">{{ title }}</a></li>
            {{ /children }}
            </ul>
            {{ endif }}
        </li>
        {{ /navigation:links }}
        </ul>
    </div>
</div>
<script>
    $(function(){
        $(document).scroll(function(){
           if($(window).scrollTop()>250){
               $('#sidebar').css({ 'margin-top':$(window).scrollTop()+'px'});
           }else{
               $('#sidebar').css({ 'margin-top':'250px'});
           } 
        });
    });
    </script>