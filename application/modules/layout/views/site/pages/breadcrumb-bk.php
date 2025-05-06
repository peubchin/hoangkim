<?php if (isset($breadcrumbs) && is_array($breadcrumbs) && !empty($breadcrumbs)): ?>
    <section>
        <div class="bg-intro">
            <div class="container">
                <div class="title-intro">
                    <ol class="breadcrumb">
                        <?php
                        $end_breadcrumbs = count($breadcrumbs);
                        $start_breadcrumbs = 0;
                        foreach ($breadcrumbs as $breadcrumb):
                            $start_breadcrumbs++;
                            ?>
                            <li<?php echo ($start_breadcrumbs === $end_breadcrumbs) ? ' class="active"' : ''; ?>><a class="text-white-tab" href="<?php echo $breadcrumb['url']; ?>"><?php echo convert_to_uppercase($breadcrumb['name']); ?></a></li>
                            <?php endforeach; ?>
                    </ol>
                    <h3><?php echo isset($breadcrumbs[$end_breadcrumbs - 1]['name']) ? convert_to_uppercase($breadcrumbs[$end_breadcrumbs - 1]['name']) : ''; ?></h3>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>