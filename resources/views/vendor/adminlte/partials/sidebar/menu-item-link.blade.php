<li @if(isset($item['id'])) id="{{ $item['id'] }}" @endif class="nav-item">

    <router-link :to="{ name:'<?php echo $item['url'] ?>' }" @if(isset($item['exact']) && $item['exact']) exact @endif class="nav-link  @if(isset($item['shift'])) {{ $item['shift'] }} @endif" active-class="active">
       
       <i class="{{ $item['icon'] ?? 'far fa-fw fa-circle' }} {{
            isset($item['icon_color']) ? 'text-'.$item['icon_color'] : ''
        }}"></i>

        <p>
            {{ $item['text'] }}

            @if(isset($item['label']))
                <span class="badge badge-{{ $item['label_color'] ?? 'primary' }} right">
                    {{ $item['label'] }}
                </span>
            @endif
        </p>

    </router-link>   

</li>


