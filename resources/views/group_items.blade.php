@extends("layout.app")
@section("browser-title", $group_name. " group items")
@section("content")
    <div class="row mt-5">
        <div class="col-sm-12 col-md-8">
            <h4 class="font-weight-bold">{{$group_name}} items</h4>
            <p class="text-small">That drop in the Abyssal Deadspace
            </p>
        </div>
        <div class="col-md-4">
            <a href="{{route("item_all")}}" class="btn float-right btn-outline-secondary group_link">All items</a>
        </div>
        <div class="col-sm-12">
            <div class="card card-body border-0 shadow-sm p-0">
                <table class="table table-striped table-sm m-0 table-hover table-responsive-sm">
                    <tr>
                        <th colspan="2">Name</th>
                        <th class="text-right">Sell price</th>
                        <th class="text-right">Buy price</th>
                        <th class="text-center">Drop rate</th>
                    </tr>
                    @foreach($items as $item)
                        <tr class="action-hover-only">
                            <td class="text-center" style="width: 48px;"><img
                                    src="https://imageserver.eveonline.com/Type/{{$item->ITEM_ID}}_32.png" alt=""></td>
                            <td><a href="{{route("item_single", ["item_id" => $item->ITEM_ID])}}">{{$item->NAME}}</a>
                            </td>
                            <td class="text-right">{{number_format($item->PRICE_SELL, 0, ",", " ")}} ISK</td>
                            <td class="text-right">{{number_format($item->PRICE_BUY, 0, ",", " ")}} ISK</td>
                            <td class="text-center">{{round($item->DROP_RATE*100,2)}}%</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection

@section("styles")
    <style>
        a.group_link {
            position: relative;
            top: 8px;
        }

    </style>
@endsection
