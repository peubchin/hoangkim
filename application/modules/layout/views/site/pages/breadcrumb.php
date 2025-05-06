<?php if (isset($breadcrumbs) && is_array($breadcrumbs) && !empty($breadcrumbs)): ?>
  <div class="box-breadcrumb">
    <div class="row">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <?php
          $end_breadcrumbs = count($breadcrumbs);
          $start_breadcrumbs = 0;
          foreach ($breadcrumbs as $breadcrumb) {
            $class_active = '';
            $start_breadcrumbs++;
            $name = mb_strtolower($breadcrumb['name'], 'UTF-8');
            if ($start_breadcrumbs === $end_breadcrumbs) {
              echo  "<li class='breadcrumb-item'>".ucfirst($name)."</li>";
            } else {
              echo "<li class='breadcrumb-item active'><a href='" . $breadcrumb['url'] . "'>" . ucfirst($name) . "</a></li>";
            }
          }
          ?>
        </ol>
      </nav>
    </div>
  </div>
<?php endif; ?>
