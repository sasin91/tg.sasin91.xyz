function generateBrowserFingerprint() {
    const fingerprintData = [];

    fingerprintData.push(navigator.userAgent);
    fingerprintData.push(`${screen.width}x${screen.height}`);
    fingerprintData.push(screen.colorDepth);
    fingerprintData.push(Intl.DateTimeFormat().resolvedOptions().timeZone);
    fingerprintData.push(navigator.language);

    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    ctx.textBaseline = 'top';
    ctx.font = '16px Arial';
    ctx.fillStyle = '#f60';
    ctx.fillRect(0, 0, 100, 100);
    ctx.fillStyle = '#069';
    ctx.fillText('Browser fingerprinting', 2, 12);
    ctx.strokeStyle = '#ff0000';
    ctx.arc(50, 50, 50, 0, Math.PI * 2, true);
    ctx.stroke();
    const canvasData = canvas.toDataURL();
    fingerprintData.push(canvasData);

    const gl = canvas.getContext('webgl');
    if (gl) {
        const debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
        fingerprintData.push(gl.getParameter(debugInfo.UNMASKED_VENDOR_WEBGL));
        fingerprintData.push(gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL));
    }

    return fingerprintData.join('::');
}