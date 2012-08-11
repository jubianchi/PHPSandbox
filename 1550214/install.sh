#!/bin/sh

TARGET="/usr/local/bin"
if [ "$1" ]
then
    TARGET="$1"
fi

echo '#!/bin/sh\nphp -dxdebug.remote_autostart=On $*' | sudo tee $TARGET/xdebug && sudo chmod +x $TARGET/xdebug
