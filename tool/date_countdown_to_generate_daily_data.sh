#!/bin/bash

ask() {
    # http://djm.me/ask
    local prompt default REPLY

    while true; do

        if [ "${2:-}" = "Y" ]; then
            prompt="Y/n"
            default=Y
        elif [ "${2:-}" = "N" ]; then
            prompt="y/N"
            default=N
        else
            prompt="y/n"
            default=
        fi

        # Ask the question (not using "read -p" as it uses stderr not stdout)
        echo -n "$1 [$prompt] "

        # Read the answer (use /dev/tty in case stdin is redirected from somewhere else)
        read REPLY </dev/tty

        # Default?
        if [ -z "$REPLY" ]; then
            REPLY=$default
        fi

        # Check if the reply is valid
        case "$REPLY" in
            Y*|y*) return 0 ;;
            N*|n*) return 1 ;;
        esac

    done
}
BIN_PHP=`which php`

cnt=$1
if [ -z "$cnt" ] ; then
	cnt=5
fi

for (( c=0 ; c < cnt ; c++ ))
do
	target_date=`date +%Y-%m-%d -d "-$c days" `
	target_weekday=`date +%u -d "-$c days" `
	if [ "$target_weekday" -eq "7" ]; then # Sunday
		continue
	fi
	cmd="php ../php/index.php Task/Corporate_info/daily/$target_date > /dev/null"
	echo "[Task] $target_date: $cmd"
done

if ask "Wanna fetch data?" N; then
	for (( c=0 ; c < cnt ; c++ ))
	do
		target_date=`date +%Y-%m-%d -d "-$c days" `
		cmd="$BIN_PHP ../php/index.php Task/Corporate_info/daily/$target_date > /dev/null"
		echo "[Task] $target_date: $cmd"
		eval $cmd
	done

else
	echo "Bye!"
fi
