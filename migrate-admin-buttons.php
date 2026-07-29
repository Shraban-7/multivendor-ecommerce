<?php

/**
 * Migrate admin Blade button utility chains to the shared .btn component system.
 *
 * Usage: php migrate-admin-buttons.php [--apply]
 */

$apply = in_array('--apply', $argv ?? [], true);
$root = __DIR__.'/resources/views/admin';

$visual = [
    'inline-flex', 'flex', 'items-center', 'justify-center', 'justify-between',
    'gap-1', 'gap-1.5', 'gap-2', 'gap-3',
    'px-2', 'px-2.5', 'px-3', 'px-4', 'px-5', 'px-6',
    'py-0.5', 'py-1', 'py-1.5', 'py-2', 'py-2.5', 'py-3',
    'text-xs', 'text-sm', 'text-base',
    'font-medium', 'font-semibold', 'font-bold',
    'rounded', 'rounded-xs', 'rounded-sm', 'rounded-md', 'rounded-lg', 'rounded-full',
    'border', 'border-0', 'border-border', 'border-brand', 'border-feedback-danger',
    'border-feedback-success', 'border-feedback-warning',
    'bg-brand', 'bg-brand-deep', 'bg-brand-tint',
    'bg-surface-muted', 'bg-white', 'bg-transparent',
    'bg-feedback-danger', 'bg-feedback-success', 'bg-feedback-warning', 'bg-feedback-info',
    'bg-red-50', 'bg-emerald-50', 'bg-amber-50',
    'text-white', 'text-ink', 'text-ink-secondary', 'text-ink-tertiary',
    'text-brand', 'text-feedback-danger', 'text-feedback-success', 'text-feedback-warning', 'text-feedback-info',
    'hover:bg-brand', 'hover:bg-brand-deep', 'hover:bg-surface-muted', 'hover:bg-border/30',
    'hover:bg-red-700', 'hover:bg-green-700', 'hover:bg-yellow-700',
    'hover:bg-feedback-danger', 'hover:bg-feedback-success', 'hover:text-white', 'hover:text-brand',
    'focus:outline-none', 'focus:ring-1', 'focus:ring-2', 'focus:ring-brand-tint', 'focus:ring-brand-deep',
    'disabled:opacity-50', 'transition', 'transition-colors', 'shadow-sm', 'shadow-lg',
    'w-full', 'whitespace-nowrap', 'cursor-pointer',
];

$stats = ['files' => 0, 'changed' => 0];

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if (! $file->isFile() || ! str_ends_with($file->getFilename(), '.blade.php')) {
        continue;
    }

    $path = $file->getPathname();
    $original = file_get_contents($path);

    $content = preg_replace_callback(
        '/<(button|a|label|input)\b((?:"[^"]*"|\'[^\']*\'|[^>"\'])*)>/i',
        function ($m) use ($visual, &$stats) {
            $tag = $m[1];
            $attrs = $m[2];

            if (! preg_match('/\bclass=(["\'])(.*?)\1/s', $attrs, $cm)) {
                return $m[0];
            }

            $quote = $cm[1];
            $classStr = $cm[2];

            // Skip Blade-interpolated class attributes — handle manually if needed.
            if (str_contains($classStr, '{{') || str_contains($classStr, '{!!')) {
                return $m[0];
            }

            $tokens = preg_split('/\s+/', trim($classStr)) ?: [];

            // Skip badges / pills.
            if (preg_grep('/^badge/', $tokens) || in_array('py-0.5', $tokens, true)) {
                return $m[0];
            }

            $hasTw = (bool) array_intersect($tokens, [
                'inline-flex', 'bg-brand-deep', 'bg-brand', 'bg-surface-muted',
                'bg-feedback-danger', 'bg-feedback-success', 'bg-feedback-warning', 'bg-feedback-info',
                'border-brand', 'border-feedback-danger', 'hover:bg-brand', 'hover:bg-border/30',
            ]);

            $hasBtn = (bool) preg_grep('/^btn(-|$)/', $tokens);

            // Already a clean component button with no TW visual noise.
            if ($hasBtn && ! $hasTw) {
                // Still strip redundant d-inline-flex / align-items utility leftovers.
                $clean = array_values(array_filter($tokens, function ($t) {
                    return ! in_array($t, [
                        'd-inline-flex', 'd-flex', 'align-items-center', 'justify-content-center',
                        'gap-1', 'gap-2',
                    ], true);
                }));

                if ($clean === $tokens) {
                    return $m[0];
                }

                $newClass = implode(' ', $clean);
                $newAttrs = preg_replace('/\bclass=(["\']).*?\1/s', 'class='.$quote.$newClass.$quote, $attrs, 1);
                $stats['changed']++;

                return '<'.$tag.$newAttrs.'>';
            }

            if (! $hasTw && ! $hasBtn) {
                return $m[0];
            }

            $keep = [];
            $variant = null;
            $size = null;
            $mods = [];
            $isOutline = false;
            $isDangerText = false;
            $isRound = false;

            foreach ($tokens as $t) {
                if ($t === '' || in_array($t, $visual, true)) {
                    if (in_array($t, ['px-3', 'py-1.5', 'text-xs'], true)) {
                        $size = $size ?: 'btn-sm';
                    }
                    if ($t === 'rounded-full') {
                        $isRound = true;
                    }
                    if ($t === 'w-full') {
                        $mods[] = 'btn-block';
                    }
                    if (in_array($t, ['bg-brand-deep', 'bg-brand'], true)) {
                        $variant = 'btn-primary';
                    } elseif ($t === 'bg-feedback-danger') {
                        $variant = 'btn-danger';
                    } elseif ($t === 'bg-feedback-success') {
                        $variant = 'btn-success';
                    } elseif ($t === 'bg-feedback-warning') {
                        $variant = 'btn-warning';
                    } elseif ($t === 'bg-feedback-info') {
                        $variant = 'btn-info';
                    } elseif ($t === 'bg-surface-muted' || ($t === 'bg-white' && in_array('border', $tokens, true))) {
                        $variant = $variant ?: 'btn-light';
                    } elseif ($t === 'border-brand' || ($t === 'text-brand' && in_array('border', $tokens, true) && ! in_array('bg-brand-deep', $tokens, true))) {
                        $isOutline = true;
                        $variant = 'btn-outline-primary';
                    } elseif ($t === 'border-feedback-danger' || ($t === 'text-feedback-danger' && in_array('border', $tokens, true) && ! in_array('bg-feedback-danger', $tokens, true))) {
                        $isOutline = true;
                        $variant = 'btn-outline-danger';
                    } elseif ($t === 'text-feedback-danger' && ! in_array('bg-feedback-danger', $tokens, true)) {
                        $isDangerText = true;
                    }

                    continue;
                }

                if ($t === 'btn' || str_starts_with($t, 'btn-')) {
                    if (in_array($t, ['btn-sm', 'btn-lg'], true)) {
                        $size = $t;
                    } elseif (in_array($t, ['btn-icon', 'btn-block', 'btn-round', 'btn-danger-text'], true)) {
                        $mods[] = $t;
                    } elseif ($t === 'btn-sm-icon') {
                        $size = 'btn-sm';
                        $mods[] = 'btn-icon';
                    } elseif ($t !== 'btn') {
                        $variant = $t;
                    }

                    continue;
                }

                // Bootstrap leftovers
                if (in_array($t, ['d-inline-flex', 'd-flex', 'align-items-center', 'justify-content-center'], true)) {
                    continue;
                }

                $keep[] = $t;
            }

            if (! $variant) {
                if ($isOutline) {
                    $variant = 'btn-outline-primary';
                } elseif (in_array('border', $tokens, true) || in_array('border-border', $tokens, true)) {
                    $variant = 'btn-light';
                } else {
                    // Not clearly a button.
                    return $m[0];
                }
            }

            if ($isDangerText && $variant === 'btn-light') {
                $mods[] = 'btn-danger-text';
            }

            if ($isRound) {
                $mods[] = 'btn-round';
            }

            $mods = array_values(array_unique($mods));

            $newTokens = array_merge(['btn', $variant], $size ? [$size] : [], $mods, $keep);
            $newClass = implode(' ', array_filter($newTokens));

            $newAttrs = preg_replace('/\bclass=(["\']).*?\1/s', 'class='.$quote.$newClass.$quote, $attrs, 1);
            $stats['changed']++;

            return '<'.$tag.$newAttrs.'>';
        },
        $original
    );

    if ($content !== $original) {
        $stats['files']++;
        $rel = str_replace(__DIR__.DIRECTORY_SEPARATOR, '', $path);
        echo ($apply ? 'UPDATED' : 'DRY   ').' '.$rel.PHP_EOL;

        if ($apply) {
            file_put_contents($path, $content);
        }
    }
}

echo PHP_EOL.sprintf(
    'files=%d replacements=%d mode=%s'.PHP_EOL,
    $stats['files'],
    $stats['changed'],
    $apply ? 'APPLY' : 'DRY-RUN'
);
