@foreach($items as $item)
    <li @if (isset($item->attributes['class']) || $item->hasChildren()) class="
            @if(isset($item->attributes['class'])){{ $item->attributes['class'] }}
            @endif
            @if($item->hasChildren()){{ 'dropdown' }}
            @endif"
        @endif>
        <a href="{!! $item->url() !!}"
            @if($item->hasChildren())  class="dropdown-toggle" data-toggle="dropdown" @endif
        >
        @if (isset($item->attributes['icon'])) <i class="{{ $item->attributes['icon'] }}"></i>
        @endif {!! $item->title !!}
        @if($item->hasChildren()) <span class="caret"></span> @endif
        </a>
        @if($item->hasChildren())
            <ul class="dropdown-menu dropdown-menu-right">
                @include('admin.partials.menu-items', array('items' => $item->children()))
            </ul>
        @endif
    </li>
@endforeach

