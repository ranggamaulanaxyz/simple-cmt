@extends('layouts.app')
@section('title', 'Edit Laporan')
@section('page-title', 'Edit Laporan')

@section('content')
    @if($errors->any())
        <div class="mb-8 p-4 bg-error-bg rounded-xl flex flex-col gap-2" style="border-left: 4px solid #DC2626;">
            <div class="flex items-center gap-2 mb-1">
                <span class="material-symbols-outlined text-error text-lg font-bold">warning</span>
                <p class="text-sm font-bold text-error uppercase tracking-wider">Terjadi Kesalahan Validasi</p>
            </div>
            <ul class="list-disc list-inside text-xs text-error/80 space-y-1 ml-1 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reports.update', $report) }}" enctype="multipart/form-data" x-data="{
                                      type: '{{ old('type', $report->type) }}',
                                      rcDirections: {{ json_encode(old('rc_directions', $report->rcDirections->count() ? $report->rcDirections->toArray() : [['arah_remote_control' => '', 'penyulang' => '']])) }},
                                      rcCommissionings: {{ json_encode(old('rc_commissionings', $report->rcCommissionings->count() ? $report->rcCommissionings->toArray() : [])) }},
                                      pointsMap: @js($rcPoints->pluck('typeSignal.name', 'id')),
                                      stdPoints: @js($rcPoints->pluck('id')),

                                      init() {
                                          // Map initial rc_direction_id to _dir_id for existing records
                                          this.rcCommissionings.forEach(comm => {
                                              if (comm.rc_direction_id) {
                                                  const dirIdx = this.rcDirections.findIndex(d => d.id === comm.rc_direction_id);
                                                  if (dirIdx !== -1) comm._dir_id = dirIdx;
                                              }
                                          });
                                      },

                                      addDirection() {
                                          const newIdx = this.rcDirections.length;
                                          this.rcDirections.push({arah_remote_control: '', penyulang: ''});
                                          this.syncPointsForDirection('', newIdx);
                                      },

                                      removeDirection(index) {
                                          const dir = this.rcDirections[index];
                                          this.rcDirections.splice(index, 1);
                                          // Remove commissionings associated with this index or direction name
                                          this.rcCommissionings = this.rcCommissionings.filter(c => {
                                              return c._dir_id !== index && c.arah_remote_control !== dir.arah_remote_control;
                                          });
                                          // Re-index remaining commissionings
                                          this.rcCommissionings.forEach(c => {
                                              if (c._dir_id > index) c._dir_id--;
                                          });
                                      },

                                      syncPointsForDirection(name, dirIdx) {
                                          this.stdPoints.forEach(pId => {
                                              this.rcCommissionings.push({
                                                  database_field: '',
                                                  point_id: pId,
                                                  terminal_kubiker: '',
                                                  signaling_gh: false,
                                                  signaling_dcc: false,
                                                  control_dcc_rtu: false,
                                                  control_dcc_rele_plat: false,
                                                  control_dcc_lbs: false,
                                                  arah_remote_control: name,
                                                  note: '1',
                                                  _dir_id: dirIdx
                                              });
                                          });
                                      }
                                  }">
        @csrf
        @method('PUT')

        <div class="space-y-8">
            {{-- General Info --}}
            <div class="bg-white rounded-xl p-8 shadow-sm">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl font-bold text-on-surface font-display">Informasi Umum</h2>
                    <div class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 {{ $report->isRc() ? 'bg-primary/10 text-primary' : 'bg-secondary/10 text-secondary' }}">
                        <span class="material-symbols-outlined text-[14px]">{{ $report->isRc() ? 'settings_remote' : 'electrical_services' }}</span>
                        {{ $report->isRc() ? 'Remote Control' : 'Ground Fault Detector' }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <input type="hidden" name="type" value="{{ $report->type }}">

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface/50 mb-2">Tanggal</label>
                        <input type="date" name="date" value="{{ old('date', $report->date->format('Y-m-d')) }}" required class="w-full bg-surface-container-low rounded-md py-3.5 px-4 text-sm border-none focus:ring-2 focus:ring-primary/20 transition-all">
                        @error('date') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface/50 mb-2" x-text="type === 'gfd' ? 'Nomor Gardu' : 'Gardu'">Gardu</label>
                        <input type="text" name="gardu" value="{{ old('gardu', $report->gardu) }}" required class="w-full bg-surface-container-low rounded-md py-3.5 px-4 text-sm border-none focus:ring-2 focus:ring-primary/20 transition-all">
                        @error('gardu') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- GFD Specific Fields in General Info --}}
                    <template x-if="type === 'gfd'">
                        <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface/50 mb-2">Arah Gardu</label>
                                <input type="text" name="arah_gardu" value="{{ old('arah_gardu', $report->arah_gardu) }}" class="w-full bg-surface-container-low rounded-md py-3.5 px-4 text-sm border-none focus:ring-2 focus:ring-secondary/20 transition-all" placeholder="Utara/Selatan/dll">
                                @error('arah_gardu') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface/50 mb-2">Jenis Pekerjaan</label>
                                <input type="text" name="task_type" value="{{ old('task_type', $report->task_type) }}" class="w-full bg-surface-container-low rounded-md py-3.5 px-4 text-sm border-none focus:ring-2 focus:ring-secondary/20 transition-all">
                                @error('task_type') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface/50 mb-2">Penyulang</label>
                                <input type="text" name="penyulang" value="{{ old('penyulang', $report->penyulang) }}" class="w-full bg-surface-container-low rounded-md py-3.5 px-4 text-sm border-none focus:ring-2 focus:ring-secondary/20 transition-all">
                                @error('penyulang') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface/50 mb-2">Gardu Induk</label>
                                <input type="text" name="gardu_induk" value="{{ old('gardu_induk', $report->gardu_induk) }}" class="w-full bg-surface-container-low rounded-md py-3.5 px-4 text-sm border-none focus:ring-2 focus:ring-secondary/20 transition-all">
                                @error('gardu_induk') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </template>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface/50 mb-2">Alamat</label>
                        <textarea name="address" rows="2" required class="w-full bg-surface-container-low rounded-md py-3.5 px-4 text-sm border-none focus:ring-2 focus:ring-primary/20 transition-all resize-none">{{ old('address', $report->address) }}</textarea>
                        @error('address') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 md:col-span-2">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface/50 mb-2">Latitude</label>
                            <input type="text" name="latitude" value="{{ old('latitude', $report->latitude) }}" class="w-full bg-surface-container-low rounded-md py-3.5 px-4 text-sm border-none focus:ring-2 focus:ring-primary/20 transition-all">
                            @error('latitude') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface/50 mb-2">Longitude</label>
                            <input type="text" name="longitude" value="{{ old('longitude', $report->longitude) }}" class="w-full bg-surface-container-low rounded-md py-3.5 px-4 text-sm border-none focus:ring-2 focus:ring-primary/20 transition-all">
                            @error('longitude') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="md:col-span-2" x-show="type === 'rc'">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface/50 mb-2">Catatan Umum</label>
                        <input type="text" name="notes" value="{{ old('notes', $report->notes) }}" class="w-full bg-surface-container-low rounded-md py-3.5 px-4 text-sm border-none focus:ring-2 focus:ring-primary/20 transition-all">
                        @error('notes') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Denah & SLD / Layout dan Denah Upload (Shared) --}}
            <div class="bg-white rounded-xl p-8 shadow-sm">
                <h2 class="text-xl font-bold text-on-surface mb-6 font-display" x-text="type === 'gfd' ? 'Upload Layout dan Denah' : 'Upload Denah & SLD'">Upload Denah & SLD</h2>
                <div x-data="{ fileName: '' }">
                    @if($report->denah_sld_file)
                        <div class="mb-6 p-4 bg-primary/5 rounded-2xl flex items-center justify-between border border-primary/10 shadow-sm animate-fade-in">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm">
                                    <span class="material-symbols-outlined text-primary text-2xl">description</span>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-on-surface/70">File saat ini tersedia</p>
                                    <p class="text-[10px] text-on-surface/40 uppercase tracking-widest">Gardu Distribusi Dokumen</p>
                                </div>
                            </div>
                            <a href="{{ Storage::url($report->denah_sld_file) }}" target="_blank" class="px-4 py-2 bg-primary text-white rounded-lg text-xs font-bold hover:shadow-md transition-all flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px]">visibility</span>
                                Lihat
                            </a>
                        </div>
                    @endif
                    <label class="relative group flex flex-col items-center justify-center w-full h-32 bg-surface-container-low rounded-2xl cursor-pointer hover:bg-primary/5 transition-all outline-none" style="border: 2px dashed rgba(124,58,237,0.15);">
                        <div class="text-center p-6">
                            <p class="text-[10px] text-on-surface/60 font-bold uppercase tracking-widest" x-text="fileName || 'Ganti Denah & SLD (XLSX/XLS, Max 5MB)'"></p>
                        </div>
                        <input type="file" name="denah_sld_file" class="hidden" accept=".xls,.xlsx" @change="fileName = $event.target.files[0]?.name">
                    </label>
                    @error('denah_sld_file') <p class="text-xs text-error mt-2 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- RC Specific Sections --}}
            <div x-show="type === 'rc'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                {{-- Arah Remote Control List --}}
                <div class="bg-white rounded-xl p-8 shadow-sm">
                    <h2 class="text-xl font-bold text-on-surface mb-6 font-display">Arah Remote Control</h2>
                    <template x-for="(dir, index) in rcDirections" :key="index">
                        <div class="flex gap-4 mb-4 items-end bg-surface-container-low/30 p-4 rounded-xl border border-outline-variant/10">
                            <div class="flex-1">
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface/50 mb-2">Arah</label>
                                <input type="text" :name="`rc_directions[${index}][arah_remote_control]`" x-model="dir.arah_remote_control" class="w-full bg-white rounded-lg py-3 px-4 text-sm border-none shadow-sm focus:ring-2 focus:ring-primary/20">
                            </div>
                            <div class="flex-1">
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface/50 mb-2">Penyulang</label>
                                <input type="text" :name="`rc_directions[${index}][penyulang]`" x-model="dir.penyulang" class="w-full bg-white rounded-lg py-3 px-4 text-sm border-none shadow-sm focus:ring-2 focus:ring-primary/20">
                            </div>
                            <button type="button" @click="removeDirection(index)" class="w-11 h-11 flex items-center justify-center hover:bg-error/10 text-error rounded-xl transition-all" x-show="rcDirections.length > 1">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </div>
                    </template>
                    <div class="mt-4">
                        <button type="button" @click="addDirection()" class="py-2.5 px-5 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-xl text-xs font-bold transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">add</span> Tambah Arah
                        </button>
                    </div>
                </div>

                {{-- RC Commissioning Table --}}
                <div class="bg-white rounded-xl p-8 shadow-sm">
                    <h2 class="text-xl font-bold text-on-surface mb-6 font-display">Point Commissioning</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 pb-8 border-b border-outline-variant/10">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface/50 mb-2">Arah</label>
                            <input type="text" name="arah" value="{{ old('arah', $report->arah) }}" class="w-full bg-surface-container-low rounded-md py-3 px-4 text-sm border-none placeholder-on-surface/20" placeholder="Arah...">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface/50 mb-2">Lokasi</label>
                            <input type="text" name="lokasi" value="{{ old('lokasi', $report->lokasi) }}" class="w-full bg-surface-container-low rounded-md py-3 px-4 text-sm border-none placeholder-on-surface/20" placeholder="Lokasi...">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface/50 mb-2">Tanggal Commissioning</label>
                            <input type="date" name="date_commissioning" value="{{ old('date_commissioning', $report->date_commissioning?->format('Y-m-d')) }}" class="w-full bg-surface-container-low rounded-md py-3 px-4 text-sm border-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface/50 mb-2">RTU</label>
                            <input type="text" name="rtu" value="{{ old('rtu', $report->rtu) }}" class="w-full bg-surface-container-low rounded-md py-3 px-4 text-sm border-none">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface/50 mb-2">Modem</label>
                            <input type="text" name="modem" value="{{ old('modem', $report->modem) }}" class="w-full bg-surface-container-low rounded-md py-3 px-4 text-sm border-none">
                        </div>
                    </div>

                    <div class="overflow-x-auto -mx-8 px-8">
                        <table class="w-full text-left border-separate border-spacing-y-1" style="min-width: 1100px;">
                            <thead>
                                <tr class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest border-b border-outline-variant/10">
                                    <th class="pb-3 pr-2 whitespace-nowrap" rowspan="2">Database</th>
                                    <th class="pb-3 px-2 whitespace-nowrap" rowspan="2">Signal</th>
                                    <th class="pb-3 px-2 whitespace-nowrap" rowspan="2">Point Name</th>
                                    <th class="pb-3 px-2 whitespace-nowrap" rowspan="2">Terminal</th>
                                    <th class="pb-3 px-2 text-center whitespace-nowrap" colspan="2">Signaling</th>
                                    <th class="pb-3 px-2 text-center whitespace-nowrap" colspan="3">Control (DCC)</th>
                                    <th class="pb-3 px-2 whitespace-nowrap" rowspan="2">Arah</th>
                                    <th class="pb-3 px-2 text-center whitespace-nowrap" rowspan="2">Status</th>
                                    <th class="pb-3 pl-2" rowspan="2"></th>
                                </tr>
                                <tr class="text-[9px] font-bold text-on-surface/30 uppercase tracking-widest">
                                    <th class="py-2 px-1 text-center font-extrabold text-on-surface/60 bg-surface-container-low/50 rounded-tl-md">GH</th>
                                    <th class="py-2 px-1 text-center font-extrabold text-on-surface/60 bg-surface-container-low/50 rounded-tr-md">DCC</th>
                                    <th class="py-2 px-1 text-center font-extrabold text-on-surface/60 bg-primary/5 rounded-tl-md">RTU</th>
                                    <th class="py-2 px-1 text-center font-extrabold text-on-surface/60 bg-primary/5">RELE</th>
                                    <th class="py-2 px-1 text-center font-extrabold text-on-surface/60 bg-primary/5 rounded-tr-md">LBS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(comm, ci) in rcCommissionings" :key="ci">
                                    <tr class="group hover:bg-surface-container-low shadow-sm transition-all rounded-lg overflow-hidden border border-outline-variant/10">
                                        <td class="py-3 pr-2 pl-3 bg-white group-hover:bg-transparent">
                                            <input type="text" x-model="comm.database_field" :name="`rc_commissionings[${ci}][database_field]`" class="w-full bg-surface-container-low/50 rounded py-1 px-2 text-[11px] border-none focus:ring-1 focus:ring-primary/20">
                                        </td>
                                        <td class="py-3 px-2 bg-white group-hover:bg-transparent">
                                            <div class="text-[10px] font-bold text-primary/70 px-2 py-1 bg-primary/5 rounded inline-block min-w-[50px] text-center uppercase" x-text="pointsMap[comm.point_id] || '-'"></div>
                                        </td>
                                        <td class="py-3 px-2 bg-white group-hover:bg-transparent">
                                            <select x-model="comm.point_id" :name="`rc_commissionings[${ci}][point_id]`" class="w-full bg-surface-container-low/50 rounded py-1 px-2 text-[11px] border-none focus:ring-1 focus:ring-primary/20">
                                                <option value="">Pilih</option>
                                                @foreach($rcPoints as $point)
                                                    <option value="{{ $point->id }}">{{ $point->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="py-3 px-2 bg-white group-hover:bg-transparent">
                                            <input type="text" x-model="comm.terminal_kubiker" :name="`rc_commissionings[${ci}][terminal_kubiker]`" class="w-full bg-surface-container-low/50 rounded py-1 px-2 text-[11px] border-none focus:ring-1 focus:ring-primary/20">
                                        </td>
                                        {{-- Signaling --}}
                                        <td class="py-3 px-1 text-center bg-surface-container-low/10 group-hover:bg-transparent border-l border-outline-variant/10">
                                            <input type="checkbox" x-model="comm.signaling_gh" :name="`rc_commissionings[${ci}][signaling_gh]`" class="rounded border-outline-variant text-primary focus:ring-primary/20 w-4 h-4" :checked="comm.signaling_gh">
                                        </td>
                                        <td class="py-3 px-1 text-center bg-surface-container-low/10 group-hover:bg-transparent">
                                            <input type="checkbox" x-model="comm.signaling_dcc" :name="`rc_commissionings[${ci}][signaling_dcc]`" class="rounded border-outline-variant text-primary focus:ring-primary/20 w-4 h-4" :checked="comm.signaling_dcc">
                                        </td>
                                        {{-- Control --}}
                                        <td class="py-3 px-1 text-center bg-primary/5 group-hover:bg-transparent border-l border-outline-variant/10">
                                            <input type="checkbox" x-model="comm.control_dcc_rtu" :name="`rc_commissionings[${ci}][control_dcc_rtu]`" class="rounded border-outline-variant text-primary focus:ring-primary/20 w-4 h-4" :checked="comm.control_dcc_rtu">
                                        </td>
                                        <td class="py-3 px-1 text-center bg-primary/5 group-hover:bg-transparent">
                                            <input type="checkbox" x-model="comm.control_dcc_rele_plat" :name="`rc_commissionings[${ci}][control_dcc_rele_plat]`" class="rounded border-outline-variant text-primary focus:ring-primary/20 w-4 h-4" :checked="comm.control_dcc_rele_plat">
                                        </td>
                                        <td class="py-3 px-1 text-center bg-primary/5 group-hover:bg-transparent">
                                            <input type="checkbox" x-model="comm.control_dcc_lbs" :name="`rc_commissionings[${ci}][control_dcc_lbs]`" class="rounded border-outline-variant text-primary focus:ring-primary/20 w-4 h-4" :checked="comm.control_dcc_lbs">
                                        </td>
                                        <td class="py-3 px-2 bg-white group-hover:bg-transparent text-[10px]">
                                            <div class="font-bold text-on-surface/70 truncate max-w-[100px]" x-text="rcDirections[comm._dir_id]?.arah_remote_control || '-'"></div>
                                            <div class="opacity-50 truncate max-w-[100px]" x-text="rcDirections[comm._dir_id]?.penyulang || '-'"></div>
                                            <input type="hidden" :name="`rc_commissionings[${ci}][_dir_id]`" :value="comm._dir_id">
                                        </td>
                                        <td class="py-3 px-2 bg-white group-hover:bg-transparent text-center">
                                            <select x-model="comm.note" :name="`rc_commissionings[${ci}][note]`" class="bg-transparent text-[10px] font-bold uppercase border-none focus:ring-0 cursor-pointer" :class="comm.note == '1' ? 'text-success' : 'text-error'">
                                                <option value="1">OK</option>
                                                <option value="0">NOK</option>
                                            </select>
                                        </td>
                                        <td class="py-3 pl-2 pr-3 bg-white group-hover:bg-transparent text-right">
                                            <button type="button" @click="rcCommissionings.splice(ci, 1)" class="p-1 hover:bg-error/10 text-error rounded transition-colors" x-show="rcCommissionings.length > 1">
                                                <span class="material-symbols-outlined text-sm">remove_circle_outline</span>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        <button type="button" @click="rcCommissionings.push({database_field:'',point_id:'',terminal_kubiker:'',signaling_gh:false,signaling_dcc:false,control_dcc_rtu:false,control_dcc_rele_plat:false,control_dcc_lbs:false,arah_remote_control:'',note:'1'})" class="py-3 px-6 bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                            <span class="material-symbols-outlined text-lg">add_circle</span> Tambah Point Baru
                        </button>
                    </div>
                </div>

                {{-- Note Commissioning --}}
                <div class="bg-white rounded-xl p-8 shadow-sm">
                    <h2 class="text-xl font-bold text-on-surface mb-4 font-display">Catatan Commissioning</h2>
                    <textarea name="commissioning_notes" rows="3" class="w-full bg-surface-container-low rounded-xl py-4 px-5 text-sm border-none focus:ring-2 focus:ring-primary/20 transition-all resize-none shadow-inner" placeholder="Penyimpangan atau catatan khusus selama proses commissioning...">{{ old('commissioning_notes', $report->commissioning_notes) }}</textarea>
                </div>
            </div>

            {{-- GFD Specific Sections --}}
            <div x-show="type === 'gfd'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                <div class="bg-white rounded-xl p-8 shadow-sm">
                    <h2 class="text-xl font-bold text-on-surface mb-8 font-display">Data Ground Fault Detector</h2>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        {{-- GFD Lama --}}
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-lg bg-on-surface/5 flex items-center justify-center text-on-surface/40"><span class="material-symbols-outlined text-lg">history</span></div>
                                <h3 class="text-sm font-bold text-on-surface/70 uppercase tracking-widest">Kondisi GFD Lama</h3>
                            </div>
                            <div class="grid gap-4">
                                @php
                                    $gfdLamaFields = [
                                        'old_gfd' => ['label' => 'GFD', 'unit' => null],
                                        'old_gfd_type_serial_number' => ['label' => 'Type/Serial Number', 'unit' => null],
                                        'old_gfd_setting_kepekaan_arus' => ['label' => 'Kepekaan Arus', 'unit' => 'A'],
                                        'old_gfd_setting_kepekaan_waktu' => ['label' => 'Kepekaan Waktu', 'unit' => 'ms'],
                                        'old_gfd_setting_waktu' => ['label' => 'Setting Waktu (Flashing)', 'unit' => 'h'],
                                        'old_gfd_injek_arus' => ['label' => 'Injek Arus', 'unit' => 'A'],
                                        'old_gfd_condition' => ['label' => 'Kondisi Akhir', 'unit' => null],
                                    ];
                                @endphp
                                @foreach($gfdLamaFields as $field => $data)
                                    <div>
                                        <label class="block text-[10px] font-bold text-on-surface/40 mb-1.5 ml-1">{{ $data['label'] }}</label>
                                        <div class="relative">
                                            <input type="text" name="{{ $field }}" value="{{ old($field, $report->$field) }}" 
                                                class="w-full bg-surface-container-low/50 rounded-lg py-3 px-4 text-sm border-none focus:ring-2 focus:ring-secondary/20 transition-all @if($data['unit']) pr-12 @endif"
                                                placeholder="{{ $data['label'] }}...">
                                            @if($data['unit'])
                                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-bold text-on-surface/30 uppercase tracking-widest">
                                                    {{ $data['unit'] }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- GFD Baru --}}
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-8 h-8 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary"><span class="material-symbols-outlined text-lg">new_releases</span></div>
                                <h3 class="text-sm font-bold text-secondary uppercase tracking-widest">Pemasangan GFD Baru</h3>
                            </div>
                            <div class="grid gap-4">
                                @php
                                    $gfdBaruFields = [
                                        'new_gfd' => ['label' => 'GFD', 'unit' => null],
                                        'new_gfd_type_serial_number' => ['label' => 'Type/Serial Number', 'unit' => null],
                                        'new_gfd_setting_kepekaan_arus' => ['label' => 'Kepekaan Arus', 'unit' => 'A'],
                                        'new_gfd_setting_kepekaan_waktu' => ['label' => 'Kepekaan Waktu', 'unit' => 'ms'],
                                        'new_gfd_setting_waktu' => ['label' => 'Setting Waktu (Flashing)', 'unit' => 'h'],
                                        'new_gfd_injek_arus' => ['label' => 'Injek Arus', 'unit' => 'A'],
                                        'new_gfd_condition' => ['label' => 'Kondisi Akhir', 'unit' => null],
                                    ];
                                @endphp
                                @foreach($gfdBaruFields as $field => $data)
                                    <div>
                                        <label class="block text-[10px] font-bold text-secondary/50 mb-1.5 ml-1">{{ $data['label'] }}</label>
                                        <div class="relative">
                                            <input type="text" name="{{ $field }}" value="{{ old($field, $report->$field) }}" 
                                                class="w-full bg-secondary/5 rounded-lg py-3 px-4 text-sm border-none focus:ring-2 focus:ring-secondary/20 transition-all @if($data['unit']) pr-12 @endif"
                                                placeholder="{{ $data['label'] }}...">
                                            @if($data['unit'])
                                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-[10px] font-bold text-secondary/30 uppercase tracking-widest">
                                                    {{ $data['unit'] }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Uraian Pemeriksaan (GFD) --}}
                <div class="bg-white rounded-xl p-8 shadow-sm">
                    <h2 class="text-xl font-bold text-on-surface mb-6 font-display">Uraian Pemeriksaan</h2>
                    <div class="overflow-hidden rounded-xl border border-outline-variant/10 shadow-sm">
                        <table class="w-full text-left">
                            <thead class="bg-surface-container-low/50">
                                <tr class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest">
                                    <th class="py-4 px-6">Item Pemeriksaan</th>
                                    <th class="py-4 px-4 text-center">Ada</th>
                                    <th class="py-4 px-4 text-center">Tidak</th>
                                    <th class="py-4 px-4 text-center">Rusak</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/10">
                                @foreach($gfdItems as $index => $item)
                                    @php 
                                        $existing = $report->gfdInspections->firstWhere('item_id', $item->id); 
                                        $checkedValue = $existing?->ada ? 'ada' : ($existing?->tidak_ada ? 'tidak_ada' : ($existing?->rusak ? 'rusak' : ''));
                                    @endphp
                                    <tr class="hover:bg-surface-container-low/20 transition-colors" x-data="{ checked: '{{ $checkedValue }}' }">
                                        <td class="py-4 px-6 text-sm font-medium text-on-surface/80">{{ $item->name }}</td>
                                        <input type="hidden" name="gfd_inspections[{{ $index }}][item_id]" value="{{ $item->id }}">
                                        <td class="py-4 px-4 text-center">
                                            <input type="checkbox" name="gfd_inspections[{{ $index }}][ada]" value="1" 
                                                :checked="checked === 'ada'" @click="checked = (checked === 'ada' ? '' : 'ada')"
                                                class="rounded border-outline-variant text-primary focus:ring-primary/20 w-5 h-5 transition-all">
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <input type="checkbox" name="gfd_inspections[{{ $index }}][tidak_ada]" value="1" 
                                                :checked="checked === 'tidak_ada'" @click="checked = (checked === 'tidak_ada' ? '' : 'tidak_ada')"
                                                class="rounded border-outline-variant text-secondary focus:ring-secondary/20 w-5 h-5 transition-all">
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <input type="checkbox" name="gfd_inspections[{{ $index }}][rusak]" value="1" 
                                                :checked="checked === 'rusak'" @click="checked = (checked === 'rusak' ? '' : 'rusak')"
                                                class="rounded border-outline-variant text-error focus:ring-error/20 w-5 h-5 transition-all">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Catatan GFD (Moved from Informasi Umum) --}}
                <div class="bg-white rounded-xl p-8 shadow-sm">
                    <h2 class="text-xl font-bold text-on-surface mb-4 font-display">Catatan Umum</h2>
                    <textarea name="notes" rows="3" class="w-full bg-surface-container-low rounded-xl py-4 px-5 text-sm border-none focus:ring-2 focus:ring-secondary/20 transition-all resize-none shadow-inner" placeholder="Catatan atau temuan umum lainnya...">{{ old('notes', $report->notes) }}</textarea>
                    @error('notes') <p class="text-xs text-error mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Photo Documentation Section --}}
            <div class="bg-white rounded-xl p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-primary/5 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined">photo_library</span>
                    </div>
                    <h2 class="text-lg font-bold text-on-surface font-display tracking-tight">Foto Dokumentasi</h2>
                </div>

                <div x-data="{ 
                    selections: [],
                    addSelection(event) {
                        const files = event.target.files;
                        if (files.length > 0) {
                            const id = Date.now();
                            const filesArray = Array.from(files);
                            this.selections.push({
                                id: id,
                                files: filesArray,
                                descriptions: filesArray.map(() => '')
                            });

                            const input = event.target;
                            const container = document.getElementById('hidden-inputs-container');
                            input.id = 'input-' + id;
                            input.classList.add('hidden');
                            container.appendChild(input);

                            const newInput = document.createElement('input');
                            newInput.type = 'file';
                            newInput.name = 'images[]';
                            newInput.multiple = true;
                            newInput.accept = 'image/*';
                            newInput.className = 'hidden';
                            newInput.onchange = (e) => this.addSelection(e);
                            document.getElementById('upload-label').appendChild(newInput);
                        }
                    },
                    removeSelection(index, id) {
                        this.selections.splice(index, 1);
                        const input = document.getElementById('input-' + id);
                        if (input) input.remove();
                    }
                }">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
                        @foreach($report->images as $image)
                            <div class="relative group aspect-square rounded-2xl overflow-hidden border border-outline-variant/10 shadow-sm">
                                <img src="{{ Storage::url($image->image) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-3">
                                    <p class="text-[10px] text-white/80 font-medium mb-2 truncate">{{ $image->description }}</p>
                                    <button type="button" 
                                        onclick="if(confirm('Hapus foto ini?')) { 
                                            const form = document.getElementById('global-delete-image-form');
                                            form.action = '{{ route('reports.delete-image', $image) }}';
                                            form.submit();
                                        }" 
                                        class="w-full py-1.5 bg-error/90 hover:bg-error text-white rounded-lg text-[10px] font-bold transition-all flex items-center justify-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">delete</span>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-6">
                        <label id="upload-label" class="relative group flex flex-col items-center justify-center w-full h-40 bg-surface-container-low rounded-2xl cursor-pointer hover:bg-primary/5 transition-all border-2 border-dashed border-outline-variant/30 overflow-hidden">
                            <div class="absolute inset-0 bg-primary opacity-0 group-hover:opacity-[0.02] transition-opacity"></div>
                            <div class="text-center p-8">
                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm mx-auto mb-3 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined text-primary text-2xl">add_a_photo</span>
                                </div>
                                <p class="text-xs font-bold text-on-surface mb-1">Tambah Foto Dokumentasi</p>
                                <p class="text-[10px] text-on-surface/40 uppercase tracking-widest font-medium">Tambah dokumentasi baru (Bisa multiple)</p>
                            </div>
                            <input type="file" name="images[]" multiple accept="image/*" class="hidden" @change="addSelection($event)">
                        </label>
                        <div id="hidden-inputs-container" class="hidden"></div>
                    </div>

                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-for="(selection, sIdx) in selections" :key="selection.id">
                            <div class="space-y-3">
                                <template x-for="(file, fIdx) in selection.files" :key="fIdx">
                                    <div class="group relative flex items-center gap-4 p-4 bg-white rounded-2xl border border-outline-variant/10 shadow-sm hover:shadow-md transition-all animate-fade-in">
                                        <div class="w-14 h-14 bg-primary/5 rounded-xl flex items-center justify-center text-primary shadow-inner">
                                            <span class="material-symbols-outlined text-2xl">image</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-2">
                                                <p class="text-[10px] font-bold text-on-surface/60 truncate uppercase tracking-wider" x-text="file.name"></p>
                                                <button type="button" @click="removeSelection(sIdx, selection.id)" class="text-error/40 hover:text-error transition-colors">
                                                    <span class="material-symbols-outlined text-sm">delete</span>
                                                </button>
                                            </div>
                                            <input type="text" name="image_descriptions[]" x-model="selection.descriptions[fIdx]" placeholder="Tambahkan deskripsi foto..." class="w-full bg-surface-container-low/50 rounded-lg py-2 px-3 text-[11px] border-none focus:ring-2 focus:ring-primary/20 transition-all">
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center gap-4 pt-4">
                <button type="submit" name="action" value="draft" class="h-14 px-10 bg-primary hover:bg-primary-dark text-white rounded-2xl transition-all font-bold text-sm shadow-lg shadow-primary/20 active:scale-[0.97] flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">save</span>
                    Simpan Perubahan
                </button>
                <a href="{{ route('reports.show', $report) }}" class="h-14 px-8 text-on-surface-variant hover:text-on-surface font-bold text-sm transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">cancel</span>
                    Batal
                </a>
            </div>
        </div>
    </form>
    
    {{-- Global Delete Image Form --}}
    <form id="global-delete-image-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endsection