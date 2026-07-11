$bytes = [System.IO.File]::ReadAllBytes('c:\xampp\htdocs\whiteappupdated\operator_assessment\download-certificate.php')
$first5 = $bytes[0], $bytes[1], $bytes[2], $bytes[3], $bytes[4]
Write-Host "First 5 bytes: $first5"
if ($bytes[0] -eq 0xEF -and $bytes[1] -eq 0xBB -and $bytes[2] -eq 0xBF) {
    Write-Host "BOM DETECTED - removing..."
    $noBom = $bytes[3..($bytes.Length-1)]
    [System.IO.File]::WriteAllBytes('c:\xampp\htdocs\whiteappupdated\operator_assessment\download-certificate.php', $noBom)
    Write-Host "BOM removed successfully"
} else {
    Write-Host "No BOM detected - file is clean"
}
