@php
    $deptoId = $get('departamento_id');
    $departamento = $deptoId ? \App\Models\Departamento::find($deptoId) : null;
    
    $numero = $departamento?->numero ?? '—';
    $tipo = $departamento?->modelo ?? '—';
    $recamaras = $departamento?->recamaras ?? '—';
    $banos = $departamento?->banos ?? '—';
    $m2 = $departamento?->m2_totales ?? '—';
    $estatus = $departamento?->estatus ?? 'Disponible';

    $esVendido = strtolower($estatus) === 'vendido';
    
    $imagenes = [];
    if ($departamento && !empty($departamento->galeria)) {
        $galeriaRaw = $departamento->galeria;
        $imagenes = is_array($galeriaRaw) ? $galeriaRaw : (json_decode(stripslashes($galeriaRaw), true) ?? []);
    }

    $urls = array_map(function ($path) {
        return preg_match('/^https?:\/\//i', $path) ? $path : route('galeria-file', ['path' => $path]);
    }, $imagenes);

    $jsonUrls = e(json_encode(array_values($urls)));
@endphp

<div class="grid grid-cols-1 gap-6 w-full pt-4 font-sans antialiased items-start md:grid-cols-[minmax(320px,360px)_minmax(0,1fr)]">

    <div class="w-full pt-4 font-sans antialiased" style="display: table !important; width: 100% !important; border-collapse: separate; border-spacing: 20px 0;">
        <div style="display: table-row;">

        <div style="display: table-cell; width: 50%; vertical-align: top;"><br>
        <br><br>
            <div style="display: grid !important; grid-template-columns: repeat(3, minmax(0, 1fr)) !important; gap: 12px !important; width: 100% !important;">
                
                <div class="bg-gray-50 dark:bg-gray-900  border-gray-200 dark:border-gray-800 p-3 rounded-xl shadow-sm flex flex-col items-center justify-center text-center gap-2">
                    <div class="w-full text-center">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center w-full">Unidad</span>
                        <span class="block text-base font-black text-amber-600 dark:text-amber-400 mt-0.5 tracking-tight text-center w-full">{{ $numero }}</span>
                    </div>
                    <div class="flex items-center justify-center bg-amber-500/10 rounded-lg text-amber-500 flex-shrink-0 mx-auto" style="width: 52px; height: 52px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 940 640" style="width: 56px; height: 56px; object-fit: contain; display: block;"><path fill="currentColor" d="M80 88C80 74.7 90.7 64 104 64L536 64C549.3 64 560 74.7 560 88C560 101.3 549.3 112 536 112L528 112L528 528L536 528C549.3 528 560 538.7 560 552C560 565.3 549.3 576 536 576L104 576C90.7 576 80 565.3 80 552C80 538.7 90.7 528 104 528L112 528L112 112L104 112C90.7 112 80 101.3 80 88zM288 176L288 208C288 216.8 295.2 224 304 224L336 224C344.8 224 352 216.8 352 208L352 176C352 167.2 344.8 160 336 160L304 160C295.2 160 288 167.2 288 176z"/></svg>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-900  border-gray-200 dark:border-gray-800 p-3 rounded-xl shadow-sm flex flex-col items-center justify-center text-center gap-2">
                    <div class="w-full text-center">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center w-full">Tipo</span>
                        <span class="block text-base font-black text-slate-700 dark:text-slate-200 mt-0.5 tracking-tight text-center w-full">{{ $tipo }}</span>
                    </div>
                    <div class="flex items-center justify-center bg-slate-500/10 rounded-lg text-slate-500 flex-shrink-0 mx-auto" style="width: 32px; height: 32px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 940 640" style="width: 56px; height: 56px; object-fit: contain; display: block;"><path fill="currentColor" d="M155.7 160C170.3 150.8 180 134.5 180 116C180 87.3 156.7 64 128 64C99.3 64 76 87.3 76 116C76 132.7 83.8 147.5 96 157L96 576L144 576L144 512L533.6 512C548.2 512 560 500.2 560 485.6C560 481.9 559.2 478.3 557.7 474.9L496 336L557.7 197.1C559.2 193.7 560 190.1 560 186.4C560 171.8 548.2 160 533.6 160L155.7 160zM144 464L144 208L500.4 208L452.2 316.5C446.7 328.9 446.7 343.1 452.2 355.5L500.4 464L144 464z"/></svg>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-900  border-gray-200 dark:border-gray-800 p-3 rounded-xl flex flex-col items-center justify-center text-center gap-2">
                    <div class="w-full text-center">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center w-full">Recámaras</span>
                        <span class="block text-base font-black text-blue-600 dark:text-blue-400 mt-0.5 tracking-tight text-center w-full">{{ $recamaras }}</span>
                    </div>
                    <div class="flex items-center justify-center bg-blue-500/10 rounded-lg text-blue-500 flex-shrink-0 mx-auto" style="width: 32px; height: 32px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 940 640" style="width: 56px; height: 56px; object-fit: contain; display: block;"><path fill="currentColor" d="M144 336C144 288.7 109.8 249.4 64.8 241.5C72 177.6 126.2 128 192 128L448 128C513.8 128 568 177.6 575.2 241.5C530.2 249.5 496 288.7 496 336L496 368L144 368L144 336zM0 448L0 336C0 309.5 21.5 288 48 288C74.5 288 96 309.5 96 336L96 416L544 416L544 336C544 309.5 565.5 288 592 288C618.5 288 640 309.5 640 336L640 448C640 483.3 611.3 512 576 512L64 512C28.7 512 0 483.3 0 448z"/></svg>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-900  border-gray-200 dark:border-gray-800 p-3 rounded-xl flex flex-col items-center justify-center gap-2 text-center shadow-sm">
                    <div class="w-full text-center">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center w-full">Baños</span>
                        <span class="block text-base font-black text-purple-600 dark:text-purple-400 mt-0.5 tracking-tight text-center w-full">{{ $banos }}</span>
                    </div>
                    <div class="flex items-center justify-center bg-purple-500/10 rounded-lg text-purple-500 flex-shrink-0 mx-auto" style="width: 32px; height: 32px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 940 640" style="width: 56px; height: 56px; object-fit: contain; display: block;"><path fill="currentColor" d="M160 141.3C160 134 165.9 128 173.3 128C176.8 128 180.2 129.4 182.7 131.9L197.6 146.8C194 155.9 192.1 165.7 192.1 176C192.1 195.9 199.3 214 211.3 228C206 237.2 207.3 249.1 215.1 257C224.5 266.4 239.7 266.4 249 257L353 153C362.4 143.6 362.4 128.4 353 119.1C345.2 111.2 333.2 110 324 115.3C310 103.3 291.9 96.1 272 96.1C261.7 96.1 251.8 98.1 242.8 101.6L227.9 86.6C213.4 72.1 193.7 64 173.3 64C130.6 64 96 98.6 96 141.3L96 320C78.3 320 64 334.3 64 352C64 369.7 78.3 384 96 384L96 432C96 460.4 108.4 486 128 503.6L128 544C128 561.7 142.3 576 160 576C177.7 576 192 561.7 192 544L192 528L448 528L448 544C448 561.7 462.3 576 480 576C497.7 576 512 561.7 512 544L512 503.6C531.6 486 544 460.5 544 432L544 384C561.7 384 576 369.7 576 352C576 334.3 561.7 320 544 320L160 320L160 141.3z"/></svg>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-gray-900  border-gray-200 dark:border-gray-800 p-3 rounded-xl shadow-sm flex flex-col items-center justify-center gap-2">
                    <div class="w-full text-center">
                        <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center w-full">m² Totales</span>
                        <span class="block text-base font-black text-indigo-600 dark:text-indigo-400 mt-0.5 tracking-tight text-center w-full">{{ $m2 }}</span>
                    </div>
                    <div class="flex items-center justify-center bg-indigo-500/10 rounded-lg text-indigo-500 flex-shrink-0 mx-auto" style="width: 32px; height: 32px;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4 flex-shrink-0" style="width: 56px; height: 56px; display: block;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18"></path></svg>
                    </div>
                </div>

                <div class=" p-3 rounded-xl shadow-sm flex flex-col items-center justify-center gap-2 {{ $esVendido ? 'bg-rose-50 border-rose-200 dark:bg-rose-950/20 dark:border-rose-900' : 'bg-emerald-50 border-emerald-200 dark:bg-emerald-950/20 dark:border-emerald-900' }}">
                    <div class="w-full text-center">
                        <span class="block text-[10px] font-bold {{ $esVendido ? 'text-rose-500' : 'text-emerald-600 dark:text-emerald-400' }} uppercase tracking-widest text-center w-full">Estatus</span>
                        <span class="block text-base font-black {{ $esVendido ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }} mt-0.5 tracking-tight text-center w-full">{{ ucfirst($estatus) }}</span>
                    </div>
                    <div class="flex items-center justify-center rounded-lg flex-shrink-0 mx-auto {{ $esVendido ? 'bg-rose-500/20 text-rose-500' : 'bg-emerald-500/20 text-emerald-500' }}" style="width: 32px; height: 32px;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-4 h-4 flex-shrink-0" style="width: 50px; height: 50px; display: block;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

            </div>
        </div>

        <div style="display: table-cell; width: 50%; vertical-align: top;">
            @if(empty($urls))
                <div class="rounded-2xl  border-dashed border-gray-200 dark:border-gray-800 bg-gray-50/40 dark:bg-gray-900/10 p-8 text-center text-xs text-gray-400 h-full min-h-[320px] flex flex-col items-center justify-center gap-2 shadow-inner">
                    <svg style="width: 48px; height: 48px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-gray-300 dark:text-gray-600">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-medium">Selecciona una unidad para proyectar la galería de planos y renders de catálogo.</span>
                </div>
            @else
                <div x-data="{ 
                        images: @js(array_values($urls)), 
                        active: 0,
                        lightbox: false,
                        lightboxImg: '',
                        init() {
                            setInterval(() => {
                                if (!this.lightbox) {
                                    this.active = (this.active + 1) % this.images.length;
                                }
                            }, 3500);
                        }
                    }" 
                    class="relative w-full rounded-2xl overflow-hidden  border-gray-200 dark:border-gray-800 shadow-xl bg-gradient-to-b from-zinc-800 to-zinc-900 dark:from-gray-900 dark:to-black aspect-video group transition-all duration-300 flex items-center justify-center">
                    
                    <div class="w-full h-full absolute inset-0 flex items-center justify-center p-4 cursor-zoom-in z-10" 
                        @click="lightboxImg = images[active]; lightbox = true">
                        <img :src="images[active]" 
                            class="max-w-full max-h-full object-contain mx-auto my-auto select-none rounded-lg transition-all duration-500 ease-in-out transform group-hover:scale-[1.01]" 
                            alt="Render Comercial">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-black/10 pointer-events-none"></div>
                    </div>
                    <div class="align-items-right grid-col">
                        <div class="absolute left-0 top-0 w-full h-full flex items-center justify-between px-4 z-20">
                            <button type="button" @click.stop="active = (active - 1 + images.length) % images.length" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center rounded-full bg-black/25 backdrop-blur-md text-white border border-white/10 hover:bg-black/45 shadow-2xl opacity-0 group-hover:opacity-100 transition-all duration-300 z-30 transform hover:scale-110">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                            </button>
                        </div>
                        <div class="absolute right-0 top-0 w-full h-full flex items-center justify-between px-4 z-20">
                            <button type="button" @click.stop="active = (active + 1) % images.length" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center rounded-full bg-black/25 backdrop-blur-md text-white border border-white/10 hover:bg-black/45 shadow-2xl opacity-0 group-hover:opacity-100 transition-all duration-300 z-30 transform hover:scale-110">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 20px; height: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                            </button>
                        </div>


                    </div>

                    



                    <template x-teleport="body">
                        <div x-show="lightbox" 
                            x-transition.opacity.duration.300ms
                            @keydown.escape.window="lightbox = false"
                            class="fixed inset-0 w-screen h-screen bg-zinc-950/90 backdrop-blur-xl flex items-center justify-center p-4 z-[999999] select-none"
                            style="display: none;"
                            @click="lightbox = false">
                            
                            <button type="button" @click="lightbox = false" 
                                    class="absolute top-5 right-4 text-white/80 hover:text-white bg-white/10 rounded-full border border-white/10 shadow-xl z-[1000000] hover:scale-105 transition-all duration-200 flex items-center justify-center backdrop-blur-lg" style="width: 42px; height: 44px; font-size: 22px; line-height: 42px;">&times;</button>

                            <div class="relative max-w-[94vw] max-h-[86vh] flex items-center justify-center p-1" @click.stop>
                                <img :src="lightboxImg" 
                                    class="max-w-full max-h-[86vh] object-contain rounded-2xl bg-zinc-900 border border-zinc-800 shadow-2xl transition duration-300" 
                                    alt="Plano Detallado">
                            </div>
                        </div>
                    </template>

                </div>
            @endif
        </div>

        </div>
    </div>

</div>