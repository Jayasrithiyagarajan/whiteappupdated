<?php
$bg_img = abs_path(__DIR__.'/../../document/bg.jpeg'); // New portrait bg.jpg
if (!file_exists($bg_img)) $bg_img = abs_path(__DIR__.'/../../document/bg.png'); // Fallback
?>
<!DOCTYPE html><html><head><meta charset="UTF-8">
<style>
body { font-family: 'poppins', sans-serif; font-size: 10pt; color: #002B5B; }
.val { font-family: 'poppins', sans-serif; font-weight: bold; color: #002B5B; }
.eq-li { margin-bottom: 2mm; }
</style>
</head><body>

<!-- BACKGROUND ARTWORK -->
<div style="position:absolute; left:0; top:0; width:210mm; height:297mm; z-index:-10;">
  <img src="<?= $bg_img ?>" style="width:210mm; height:297mm; display:block; margin:0; padding:0;">
</div>

<!-- DYNAMIC CONTENT: MUST BE DIRECT CHILDREN OF BODY FOR MPDF ABSOLUTE POSITIONING -->

<!-- QR & Cert No -->
<div style="position:absolute; left: 15.5mm; top: 78.5mm; width: 29mm; height: 29mm;">
    <img src="<?= $certificate['qr'] ?>" style="width: 100%; height: 100%; display: block;" alt="QR">
</div>
<div class="val" style="position:absolute; left: 28mm; top: 120.3mm; font-size: 6pt; width: 18mm; text-align: left; white-space: nowrap;"><?= htmlspecialchars($certificate['certificate_no']) ?></div>

<!-- Photo -->
<?php if (!empty($certificate['photo'])): ?>
<div style="position:absolute; left: 162mm; top: 78mm; width: 35mm; height: 44mm; text-align: center;">
    <img src="<?= $certificate['photo'] ?>" style="max-width: 35mm; max-height: 44mm; margin: 0 auto; display: block;" alt="Photo">
</div>
<?php endif; ?>

<!-- Name, Company, Vessel -->
<div class="val" style="position:absolute; left:0; top: 91.5mm; width: 210mm; text-align: center; font-family: 'poppins', sans-serif; font-size: 18pt; text-transform: uppercase;">
    <?= htmlspecialchars($certificate['candidate_name']) ?>
</div>

<div class="val" style="position:absolute; left:0; top: 110mm; width: 210mm; text-align: center; font-family: 'poppins', sans-serif; font-size: 13pt; color: #C55A11; text-transform: uppercase;">
    <?= htmlspecialchars($certificate['company']['name']) ?>
</div>

<!-- Vessel Location next to text -->
<div class="val" style="position:absolute; left: 22mm; top: 122mm; width: 210mm; text-align: center; font-size: 11pt; text-transform: uppercase;">
    <?= htmlspecialchars($certificate['vessel_location']) ?>
</div>

<!-- Equipment List -->
<div style="position:absolute; left: 35mm; top: 142mm; width: 162mm; font-size: 9pt; font-weight: bold; line-height: 1.5;">
    <?php foreach ($certificate['equipment'] as $eq): ?>
        &bull; <?= htmlspecialchars(trim($eq['type'].' '.$eq['manufacturer'].' '.$eq['model'])) ?> 
        <?php if (!empty($eq['capacity'])): ?>
            (SWL: <?= htmlspecialchars($eq['capacity']) ?>)
        <?php endif; ?><br>
    <?php endforeach; ?>
</div>

<!-- Cards Row 1 -->
<div class="val" style="position:absolute; top: 195.5mm; left: 17mm; width: 53mm; text-align: center; font-size: 8pt;">
    <?= htmlspecialchars($certificate['passport']) ?>
</div>
<div class="val" style="position:absolute; top: 195.5mm; left: 70mm; width: 68mm; text-align: center; font-size: 8pt;">
    <?= htmlspecialchars($certificate['vessel_location']) ?>
</div>
<div class="val" style="position:absolute; top: 195.5mm; left: 138mm; width: 55mm; text-align: center; font-size: 8pt;">
    <?= htmlspecialchars($certificate['designation']) ?>
</div>

<!-- Cards Row 2 -->
<div class="val" style="position:absolute; top: 220.5mm; left: 17mm; width: 53mm; text-align: center; font-size: 7pt;">
    <?= htmlspecialchars($certificate['training_program']) ?>
</div>
<div class="val" style="position:absolute; top: 220.5mm; left: 70mm; width: 68mm; text-align: center; font-size: 6.5pt; line-height: 1.2;">
    <?= htmlspecialchars($certificate['assessment_standard']) ?>
</div>
<!-- Status is baked in as VALID -->

<!-- Cards Row 3 -->
<div class="val" style="position:absolute; top: 241mm; left: 19mm; width: 41mm; text-align: center; font-size: 8pt;">
    <?= htmlspecialchars($certificate['issue_date']) ?>
</div>
<div class="val" style="position:absolute; top: 241mm; left: 63mm; width: 45mm; text-align: center; font-size: 8pt;">
    <?= htmlspecialchars($certificate['expiry_date']) ?>
</div>
<div class="val" style="position:absolute; top: 241mm; left: 105mm; width: 45mm; text-align: center; font-size: 8pt;">
    <?= htmlspecialchars($certificate['validity']) ?>
</div>
<div class="val" style="position:absolute; top: 241mm; left: 154mm; width: 45mm; text-align: center; font-size: 8pt;">
    <?= htmlspecialchars($certificate['renewal_due']) ?>
</div>

<!-- Signatures -->
<?php if (!empty($certificate['signatures']['assessor']['signature'])): ?>
<div style="position:absolute; left: 30mm; top: 247mm; width: 35mm; height: 15mm;">
    <img src="<?= $certificate['signatures']['assessor']['signature'] ?>" style="width: 100%; height: 100%; margin:0; padding:0; display:block;">
</div>
<?php endif; ?>

<div class="val" style="position:absolute; top: 262mm; left: 20mm; width: 55mm; text-align: center; font-size: 9pt;">
    <?= htmlspecialchars($certificate['signatures']['assessor']['name']) ?>
</div>

<?php if (!empty($certificate['signatures']['manager']['signature'])): ?>
<div style="position:absolute; left: 145mm; top: 247mm; width: 35mm; height: 15mm;">
    <img src="<?= $certificate['signatures']['manager']['signature'] ?>" style="width: 100%; height: 100%; margin:0; padding:0; display:block;">
</div>
<?php endif; ?>

</body></html>
