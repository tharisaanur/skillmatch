@php
    $skillGroups = [
        'Manajemen & Strategi' => ['Manajemen Projek', 'Komunikasi Bisnis', 'Perencanaan Strategis', 'Negosiasi', 'Manajemen Waktu'],
        'Pemasaran & Jaringan' => ['Pemasaran Digital', 'Riset Pasar', 'SEO & SEM', 'Manajemen Medsos', 'Humas (PR)'],
        'Kreativitas & Desain' => ['Penulisan Konten', 'Figma', 'Videografi', 'Fotografi', 'Ilustrasi Digital'],
        'Programming' => ['UI/UX', 'Node.js', 'Python', 'Web Protocol', 'SQL'],
    ];
    $selectedSkills = old('skills', $selectedSkills ?? []);
    $presetSkills = array_merge(...array_values($skillGroups));
    $customSelectedSkills = array_values(array_diff($selectedSkills, $presetSkills));
@endphp
<div class="skill-options">
    @foreach(array_chunk($skillGroups, 2, true) as $column)
        <div class="skill-column">
            @foreach($column as $group => $options)
                <fieldset class="skill-group">
                    <legend>{{ $group }}</legend>
                    @foreach($options as $skill)
                        <label><input type="checkbox" name="skills[]" value="{{ $skill }}" @checked(in_array($skill, $selectedSkills, true))> {{ $skill }}</label>
                    @endforeach
                </fieldset>
            @endforeach
        </div>
    @endforeach
</div>
@if(($customSkills ?? false) && $customSelectedSkills)
    <fieldset class="skill-group custom-skills">
        <legend>Skill lainnya yang tersimpan</legend>
        @foreach($customSelectedSkills as $skill)
            <label><input type="checkbox" name="skills[]" value="{{ $skill }}" checked> {{ $skill }}</label>
        @endforeach
    </fieldset>
@endif
