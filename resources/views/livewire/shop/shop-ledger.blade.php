<div class="py-12 bg-[#2d1b0e] min-h-screen font-serif" style="background-image: url('{{ asset('images/paper-texture.png') }}'); background-blend-mode: multiply;">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

        {{-- 頂部導航與狀態 --}}
        <div class="flex flex-col md:flex-row items-center justify-between mb-8 border-b-4 border-tungning-gold pb-4 gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('shop.index') }}" wire:navigate class="btn bg-tungning-wood text-tungning-gold border-tungning-gold hover:bg-tungning-brown">
                    返回商號
                </a>
                <h2 class="text-3xl font-black text-tungning-paper tracking-widest drop-shadow-md">
                    {{ $shop->name }} - 營業帳本
                </h2>
            </div>
            <div class="flex items-center gap-6 text-tungning-paper font-bold bg-tungning-brown/50 px-6 py-2 rounded-full border border-tungning-gold/50 shadow-inner">
                <div class="flex flex-col text-sm">
                    <span>今日收入：<span class="text-yellow-400">{{ number_format($totalIncomeToday) }}</span> 文</span>
                    <span>今日支出：<span class="text-red-400">{{ number_format($totalExpenseToday) }}</span> 文</span>
                </div>
                <div class="border-l border-tungning-wood/50 h-8"></div>
                <div class="flex flex-col text-sm">
                    <span>總收入：<span class="text-yellow-400">{{ number_format($totalIncome) }}</span> 文</span>
                    <span>總支出：<span class="text-red-400">{{ number_format($totalExpense) }}</span> 文</span>
                </div>
                <div class="border-l border-tungning-gold/50 h-8"></div>
                <div class="text-lg">總淨利：
                    <span class="{{ ($totalIncome - $totalExpense) >= 0 ? 'text-green-400' : 'text-red-500' }} text-xl">
                        {{ number_format($totalIncome - $totalExpense) }}
                    </span> 文
                </div>
            </div>
        </div>

        {{-- 帳本清單 --}}
        <div class="bg-[#f4f1ea] p-8 rounded-lg border-4 border-tungning-wood shadow-2xl relative">
            <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none select-none">
                <span class="text-9xl">📖</span>
            </div>
            
            <h3 class="text-2xl font-black text-tungning-brown mb-6 tracking-widest border-b-2 border-tungning-wood/30 pb-2">交易明細</h3>

            @if($orders->isEmpty())
                <div class="text-center py-16 text-tungning-wood/70 italic font-bold">
                    <div class="text-4xl mb-4">🕸️</div>
                    尚無任何交易紀錄。努力進貨並調整價格來吸引買氣吧！
                </div>
            @else
                <div class="overflow-x-auto relative z-10">
                    <table class="table w-full text-tungning-brown">
                        <thead>
                            <tr class="bg-tungning-wood text-tungning-paper border-b-2 border-tungning-brown">
                                <th class="text-left py-3 px-4 font-bold tracking-wider">交易時間</th>
                                <th class="text-left py-3 px-4 font-bold tracking-wider">買家</th>
                                <th class="text-left py-3 px-4 font-bold tracking-wider">購買商品</th>
                                <th class="text-right py-3 px-4 font-bold tracking-wider">數量</th>
                                <th class="text-right py-3 px-4 font-bold tracking-wider">單價 (文)</th>
                                <th class="text-right py-3 px-4 font-bold tracking-wider">總計 (文)</th>
                                <th class="text-right py-3 px-4 font-bold tracking-wider">稅率</th>
                                <th class="text-right py-3 px-4 font-bold tracking-wider">稅額 (文)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr class="border-b border-tungning-brown/20 hover:bg-tungning-wood/10 transition">
                                    <td class="py-3 px-4 text-sm font-bold text-gray-600">{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td class="py-3 px-4 font-black">{{ $order->buyer_name }}</td>
                                    <td class="py-3 px-4 flex items-center gap-3">
                                        <div class="w-8 h-8 bg-white rounded border border-tungning-brown/30 flex items-center justify-center overflow-hidden">
                                            @if($order->item->image_path)
                                                <img src="{{ asset($order->item->image_path) }}" class="w-full h-full object-cover">
                                            @else
                                                📦
                                            @endif
                                        </div>
                                        <span class="font-bold">{{ $order->item->name }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-right font-black text-lg">{{ $order->quantity }}</td>
                                    <td class="py-3 px-4 text-right font-bold text-gray-600">{{ number_format($order->price) }}</td>
                                    <td class="py-3 px-4 text-right font-black text-lg {{ $order->type === 'sale' ? 'text-yellow-700' : 'text-red-600' }}">
                                        {{ $order->type === 'sale' ? '+' : '-' }}{{ number_format($order->total_amount) }}
                                    </td>
                                    <td class="py-3 px-4 text-right font-bold text-gray-600">
                                        {{ number_format((float) ($order->tax_rate ?? 0), 2) }}%
                                    </td>
                                    <td class="py-3 px-4 text-right font-bold text-red-700">
                                        {{ number_format($order->tax_amount ?? 0) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
