#!/usr/bin/env python3
"""Full-page screenshot: shoot tall, then trim the flat tail below the footer."""
import subprocess, sys, os, shutil, tempfile
from PIL import Image
CHROME = "/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"

def trim_tail(im):
    """Drop the empty space under the footer in an over-tall window.

    The first version trimmed any row with no INTERNAL variation, which on a
    dark page ate the hero as well: a band of flat near-black reads the same as
    the empty space below the page. It cut the live homepage from ~4500px to
    401px and reported a 99% pixel difference that was entirely the harness.

    Compare against the colour of the very last row instead, which is by
    definition the empty ground, and stop at the first row that differs from it.
    """
    w, h = im.size
    px = im.load()
    bg = px[w // 2, h - 1]
    def differs(y):
        for x in range(0, w, 7):          # sample, do not scan every pixel
            c = px[x, y]
            if abs(c[0]-bg[0]) + abs(c[1]-bg[1]) + abs(c[2]-bg[2]) > 12:
                return True
        return False
    last = h - 1
    while last > 0 and not differs(last):
        last -= 1
    return im.crop((0, 0, w, min(h, last + 30)))

def shoot(url, out, width=1440, tall=18000, budget=14000):
    prof = tempfile.mkdtemp(prefix="cxprof-")
    raw = out + ".raw.png"
    # Popen + process group, NOT subprocess.run(capture_output=...): Chrome's
    # helper processes inherit the pipes, so after a timeout kills the main
    # process communicate() still blocks on the helpers and the harness hangs
    # forever. DEVNULL output and a group-wide SIGKILL make the timeout real.
    proc = subprocess.Popen([CHROME, "--headless", "--disable-gpu", "--hide-scrollbars",
        "--no-first-run", "--force-color-profile=srgb",
        f"--user-data-dir={prof}", f"--window-size={width},{tall}",
        f"--virtual-time-budget={budget}", f"--screenshot={raw}", url],
        stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL,
        start_new_session=True)
    try:
        proc.wait(timeout=240)
    except subprocess.TimeoutExpired:
        import signal
        try:
            os.killpg(os.getpgid(proc.pid), signal.SIGKILL)
        except (ProcessLookupError, PermissionError):
            pass
        proc.wait()
    finally:
        shutil.rmtree(prof, ignore_errors=True)
    if not os.path.exists(raw):
        return None
    im = trim_tail(Image.open(raw).convert("RGB"))
    im.save(out)
    os.remove(raw)
    return im.size

if __name__ == "__main__":
    s = shoot(sys.argv[1], sys.argv[2], int(sys.argv[3]) if len(sys.argv) > 3 else 1440)
    print(s if s else "FAIL")
