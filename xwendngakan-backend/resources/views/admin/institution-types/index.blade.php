@extends('admin.layouts.app')

@section('title', 'جۆرەکانی خوێندنگا')

@section('content')

<div class="page-header">
    <div>
        <h1>جۆرەکانی خوێندنگا</h1>
        <div class="breadcrumb">بەڕێوەبردنی جۆر و پۆلێنی دامەزراوەکان</div>
    </div>
    <button class="btn btn-primary" onclick="openCreateModal()">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        زیادکردنی جۆر
    </button>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ناو (کوردی)</th>
                    <th>ناو (ئینگلیزی)</th>
                    <th>کۆدی جۆر (Key)</th>
                    <th>ئیمۆجی</th>
                    <th>تایبەتمەندییەکان</th>
                    <th>ڕیزبەندی</th>
                    <th>دۆخ</th>
                    <th style="text-align:left;">کردارەکان</th>
                </tr>
            </thead>
            <tbody>
                @forelse($types as $t)
                    <tr>
                        <td class="fw-bold">{{ $t->name }}</td>
                        <td class="td-muted">{{ $t->name_en ?? '-' }}</td>
                        <td dir="ltr" style="text-align:right;"><span class="badge badge-gray">{{ $t->key }}</span></td>
                        <td style="font-size:20px;">{{ $t->emoji }}</td>
                        <td>
                            @if($t->has_colleges) <span class="badge badge-info mb-1">کۆلێژ/بەشی هەیە</span> <br> @endif
                            @if($t->has_departments) <span class="badge badge-primary">بەشی سادەی هەیە</span> @endif
                            @if(!$t->has_colleges && !$t->has_departments) <span class="text-muted">-</span> @endif
                        </td>
                        <td>{{ $t->sort_order }}</td>
                        <td>
                            @if($t->is_active)
                                <span class="badge badge-success">چالاک</span>
                            @else
                                <span class="badge badge-danger">ناچالاک</span>
                            @endif
                        </td>
                        <td style="text-align:left;">
                            <div class="d-flex gap-2" style="justify-content:flex-end;">
                                <button type="button" class="btn btn-ghost btn-xs" title="دەستکاری" onclick="openEditModal({{ $t }})">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <form action="{{ route('admin.types.destroy', $t) }}" method="POST" class="confirm-action" data-confirm="دڵنیایت لە سڕینەوەی ئەم جۆرە؟">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs" title="سڕینەوە">
                                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:40px; color:var(--text-muted);">هیچ جۆرێک نەدۆزرایەوە.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $types->links('pagination::bootstrap-4') }}
</div>

<!-- Modal Form -->
<div class="modal-overlay" id="type-modal" style="display: none; align-items:flex-start; padding-top:40px; overflow-y:auto;">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title" id="modal-title">زیادکردنی جۆر</h3>
            <button class="modal-close" onclick="closeModal()"><svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        <form id="type-form" method="POST" action="{{ route('admin.types.store') }}">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group">
                    <label class="form-label">ناو (کوردی) <span class="required">*</span></label>
                    <input type="text" name="name" id="t-name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">کۆدی جۆر (Key) <span class="required">*</span></label>
                    <input type="text" name="key" id="t-key" class="form-control" dir="ltr" required>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group">
                    <label class="form-label">ناو (ئینگلیزی)</label>
                    <input type="text" name="name_en" id="t-name-en" class="form-control" dir="ltr">
                </div>
                <div class="form-group">
                    <label class="form-label">ناو (عەرەبی)</label>
                    <input type="text" name="name_ar" id="t-name-ar" class="form-control">
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div class="form-group">
                    <label class="form-label">ئیمۆجی</label>
                    <input type="text" name="emoji" id="t-emoji" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">ڕیزبەندی</label>
                    <input type="number" name="sort_order" id="t-sort" class="form-control" value="0">
                </div>
            </div>

            <div class="form-group form-check">
                <div class="form-toggle">
                    <input type="checkbox" name="has_colleges" id="t-colleges" value="1">
                    <div class="form-toggle-slider"></div>
                </div>
                <label class="form-label" style="margin:0;">خاوەن کۆلێژ / پەیمانگایە (بۆ نموونە: زانکۆ، پەیمانگا ٥ ساڵی)</label>
            </div>

            <div class="form-group form-check">
                <div class="form-toggle">
                    <input type="checkbox" name="has_departments" id="t-depts" value="1">
                    <div class="form-toggle-slider"></div>
                </div>
                <label class="form-label" style="margin:0;">خاوەن بەشی سادەیە (بۆ نموونە: قوتابخانە - وێژەیی/زانستی)</label>
            </div>

            <div class="form-group form-check mb-4">
                <div class="form-toggle">
                    <input type="checkbox" name="is_active" id="t-active" value="1" checked>
                    <div class="form-toggle-slider"></div>
                </div>
                <label class="form-label" style="margin:0;">چالاکە</label>
            </div>

            <div class="d-flex gap-2" style="justify-content: flex-end;">
                <button type="button" class="btn btn-ghost" onclick="closeModal()">پاشگەزبوونەوە</button>
                <button type="submit" class="btn btn-primary">پاشەکەوتکردن</button>
            </div>
        </form>
    </div>
</div>

<script>
const modal = document.getElementById('type-modal');
const form = document.getElementById('type-form');

function closeModal() { modal.style.display = 'none'; }

function openCreateModal() {
    document.getElementById('modal-title').textContent = 'زیادکردنی جۆر';
    form.action = "{{ route('admin.types.store') }}";
    document.getElementById('form-method').value = 'POST';
    
    document.getElementById('t-name').value = '';
    document.getElementById('t-key').value = '';
    document.getElementById('t-name-en').value = '';
    document.getElementById('t-name-ar').value = '';
    document.getElementById('t-emoji').value = '';
    document.getElementById('t-sort').value = '0';
    document.getElementById('t-colleges').checked = false;
    document.getElementById('t-depts').checked = false;
    document.getElementById('t-active').checked = true;
    
    modal.style.display = 'flex';
}

function openEditModal(t) {
    document.getElementById('modal-title').textContent = 'دەستکاریکردنی جۆر';
    form.action = `/admin/institution-types/${t.id}`;
    document.getElementById('form-method').value = 'PUT';
    
    document.getElementById('t-name').value = t.name;
    document.getElementById('t-key').value = t.key;
    document.getElementById('t-name-en').value = t.name_en || '';
    document.getElementById('t-name-ar').value = t.name_ar || '';
    document.getElementById('t-emoji').value = t.emoji || '';
    document.getElementById('t-sort').value = t.sort_order || 0;
    document.getElementById('t-colleges').checked = !!t.has_colleges;
    document.getElementById('t-depts').checked = !!t.has_departments;
    document.getElementById('t-active').checked = !!t.is_active;
    
    modal.style.display = 'flex';
}
</script>

@endsection
