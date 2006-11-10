#!/bin/bash

# prvni parametr je cislo rimskymi cislicemi v rozsahu 1-39 (I-XXXIX)
# vysledek v arabskych cislicich jde na stdout
function roman2arab
{
#	R="XIV"
	R="$1"
	len=${#R}
	res=0
	for((i=0;i<$len;i++))
	do
		chr=${R:$i:1}
		if [ $i -lt $len ]
		then
			next=${R:$i+1:1}
		else
			next=''
		fi
		if [ $i -gt 0 ]
		then
			prev=${R:$i-1:1}
		else
			prev=''
		fi

		delta=0
		case $chr in
			[iI])
				case $next in
					[vV]) delta=4;;
					[xX]) delta=9;;
					*)    delta=1;;
				esac
				;;

			[vV])
				if [ "x$prev" != "xi" -a "x$prev" != "xI" ]
				then
					delta=5
				fi
				;;

			[xX])
				if [ "x$prev" != "xi" -a "x$prev" != "xI" ]
				then
					delta=10
				fi
				;;
		esac
		res=$((res+delta))
	done
	echo $res
}

# prvni parametr je titulek, druhy parametr je odsazeni v XML
# vysledek jde na stdout
function convert_title
{
#	TITUL="Ally McBealova I (16/23) /P/"
	TITUL="$1"
	INDENT="$2"

        TYP=""
        EPIZODA=""
        SERIE=""
        MATCH0="^(.*)\ /([PRDL].*)/$"
        if [[ ${TITUL} =~ ${MATCH0} ]]
        then
                case "${BASH_REMATCH[2]}" in
                        "P") TYP="<premiere />";;
                        "R") TYP="<previously-shown />";;
                        "Dern.") TYP="<last-chance />";;
                        "L") TYP="";;
                        *) TYP="";;
                esac
                [[ -n "$TYP" ]] && TITUL="${BASH_REMATCH[1]}"
        fi
        MATCH1="^(.*)\ \(([0-9]*)\)$";
        MATCH2="^(.*)\ \(([0-9]*)/([0-9]*)\)$";
        MATCH3="^(.*)\ ([IVX]*)$";
        if [[ ${TITUL} =~ ${MATCH1} ]]
        then
                TITUL="${BASH_REMATCH[1]}"
                EPIZODA="${BASH_REMATCH[2]}"
                ((EPIZODA--))
        elif [[ ${TITUL} =~ ${MATCH2} ]]
        then
                TITUL="${BASH_REMATCH[1]}"
                EPIZODA1="${BASH_REMATCH[2]}"
                EPIZODA2="${BASH_REMATCH[3]}"
                ((EPIZODA1--))
                EPIZODA="${EPIZODA1}/${EPIZODA2}"
        fi

        if [[ ${TITUL} =~ ${MATCH3} ]]
        then
                TITUL="${BASH_REMATCH[1]}"
                SERIE="${BASH_REMATCH[2]}"
                SERIE=`roman2arab $SERIE`
                ((SERIE--))
        fi

        echo -ne "${INDENT}<title>${TITUL}</title>"
        echo -ne "\n${INDENT}<desc></desc>${CATEGORYLINE}"

        [[ -n "${EPIZODA}" || -n "${SERIE}" ]] && \
        echo -ne "\n$INDENT<episode-num system=\"xmltv_ns\"> $SERIE . $EPIZODA . </episode-num>"

        [[ -n "${TYP}" ]] && \
        echo -ne "\n${INDENT}${TYP}\n"
}
DIR=`dirname $0`
HTMLDIR=${DIR}/htmldata
XMLDIR=${DIR}/xmldata
CHNDIR=${DIR}/channels.rfclist
CHANNELS=`cat ${CHNDIR} | sed -e "s/::.*//" | while read LINE; do echo -ne "${LINE} "; done`
OFILE=
NDAYS=1
ODAYS=0
LISTCHANNELS=0
MYTEMP=`tempfile`

while [ ! "${1}" = "" ]
do
    case "${1}" in 

    --help)
        echo "Czech XMLTV grabber."
	echo "Syntax: $0 [--help] [--output FILE] [--days N] [--offset N] [--channels CHANNELS] [--listchannels]"
	echo
	echo "        --help                print this help"
	echo "        --days N              integer 0-9, number of days generated"	
	echo "        --offset N            integer 0-9, offset since today"	
	echo "        --charset CHARSET     <ascii|utf-8|iso8859-2|windows-1250>"		
	echo "        --channels CHANNELS   string separated with spaces,"
	echo "                              don't forget replace space in channel name"
	echo "                              with + character"	
	echo "        --listchannels        list all channels"
	echo "        --list-channels       list all channels in XMLTV"
	echo "        --rfc2838		    get listing using XMLTV xompt. outbut (with channel ID's - RFC2838)"
        exit -1
	;;

    --days)
	NDAYS=0${2}
	shift 2
	;;
	
    --offset)	
	ODAYS=0${2}
	shift 2
	;;

    --charset)	
	CHARSET="${2}"
	shift 2
	;;
	
    --channels)
	CHANNELS="${2}"
	shift 2
	;;

    --rfc2838)
	export RFC2838=1
	shift 1
	;;
	
    --listchannels)
	echo ${CHANNELS} | tr " " "\n"
	exit 0
	;;

    --list-channels)
	LISTCHANNELS=1 
	shift 1
	;;
	
    *)
	shift 1
	;;

    esac

done

export FORMDATE
export CMDDATE
export ENDPROG
export TZONE=`date +%z`

FORMDATE=`date -d "today 12:00" +%s`
let FORMDATE=ODAYS*86400+FORMDATE
N=0

[ "${CHARSET}" = "" ] && CHARSET="iso-8859-2"
CHARSETFILTER="recode windows-1250..${CHARSET}"
[ "${CHARSET}" = "ascii" ] && CHARSETFILTER="cstocs 1250 ascii"

ENCODING=$CHARSET
[ "${ENCODING}" = "ascii" ] && ENCODING="iso-8859-2"

echo "<?xml version=\"1.0\" encoding=\"${ENCODING}\"?>"
echo "<!DOCTYPE tv SYSTEM \"xmltv.dtd\">"
echo "<tv>"

if [ "${LISTCHANNELS}" = "1" ]
then

    export IFS=":"
    cat ${CHNDIR} \
    | while read CHANNEL P2 CHANNELID
    do
        ASCIICHANNEL=`echo "${CHANNEL}" | cstocs il2 ascii`
        echo -e "\t<channel id='${CHANNELID}'><display-name lang='cz'>${ASCIICHANNEL}</display-name></channel>"
    done
    echo "</tv>"
    exit 0
fi

while [ 0${N} -lt 0${NDAYS} ]
do

    CMDDATE=`date -d "70-1-1 ${FORMDATE} seconds" +"%Y%m%d"`
    TVDATE=`date -d "70-1-1 ${FORMDATE} seconds" +"%Y-%m-%d"`
    let N=N+1

    for CHANNEL in $CHANNELS
    do

        export CHLINE=`grep "::${CHANNEL}$" ${CHNDIR}`
	[ -n "${CHLINE}" ] || export CHLINE=`grep "^${CHANNEL}::" ${CHNDIR}`

	CHANNELID=${CHLINE##*::}

	if [[ -n "${CHANNELID}" ]]
	then
	
	    [[ "${CHANNEL}" = "${CHANNELID}" ]] && export RFC2838=1

	    CHANNEL=${CHLINE%%::*}
	
    	    export ASCIICHANNEL=`echo "${CHANNEL}" | sed -e "s/%C8/C/g;s/%D3/0/g;s/%E8/c/g;s/%ED/i/g;s/%9A/s/g;s/%9D/t/g;s/%2B/ Plus/g;s/+/ /g" | cstocs il2 ascii`
    	    if [ "${RFC2838}" = "1" ]
	    then
		[ 0${N} -eq 1 ] && echo -e "\t<channel id='${CHANNELID}'><display-name lang='cz'>${ASCIICHANNEL}</display-name></channel>"
	    else
		export CHANNELID=$ASCIICHANNEL 
	    fi

	    HTMLDATA="${HTMLDIR}/tv_${CHANNELID}_${CMDDATE}.html"
	    export XMLDATA="${XMLDIR}/tv_${CHANNELID}_${CMDDATE}_${CHARSET}.xml"
            URL="http://tv.sms.cz/index.php?datum=${TVDATE}&casod=-1"
#	    URL="http://365dni.cz/index.php?typ=televize&formular_datum=${FORMDATE}&formular_casod=0&formular_typprg=&televize_tvarray=${CHANNEL}"    

	    if [ ! -f "${XMLDATA}" ]
	    then
	
		[ -f $HTMLDATA ] || wget --no-cookies --header "Cookie: P_cookies_televize_stanice=${CHANNEL}" -q -O - "${URL}" | while read LINE; do echo -n $LINE; done > ${HTMLDATA}

		# PROGRAMY

    		export PROGLINE=""
    		export CATEGORYLINE=""
		export DESCLINE=""
		export TITLELINE=""
    
#<table class="porad" onclick="location='http://tv.sms.cz/index.php?P_id_kategorie=56456&amp;P_soubor=%2Ftelevize%2Fporad.php%3Fdatum%3D2006-11-03%26id%3D820022818'" style="cursor:pointer"><tr><td class="cas" rowspan="2">05.00<img src="bmp/typprg/l.gif" style="margin-top:4px" alt="Zábava" /></td><td class="nazev"><a href="http://tv.sms.cz/index.php?P_id_kategorie=56456&amp;P_soubor=%2Ftelevize%2Fporad.php%3Fdatum%3D2006-11-03%26id%3D820022818" name="tv_porad820022818">Sama doma</td></tr><tr><td class="info1">Být doma neznamená hloupnout</td></tr></table>

    		cat ${HTMLDATA} \
                  | perl -p -i -e "s/<td class=\"cas\" rowspan=\"2\">([0-9][0-9]\.[0-9][0-9])/\n_TIME \1\n/g; \
                                   s/<img src=\"bmp\/typprg\/([^\"]*)[^>]*><\/td>*/\n_CATEGORY \1\n/g; \
                                   s/<td class=\"nazev\"><a href[^>]*>([^<]*)/\n_TITLE \1\n/g; \
                                   s/<tr><td class=\"info1\">([^<]*)/\n_DESC \1\n/g; \
                                   s/<\/td><\/tr><\/table>/\n_END \n/g" \
                  | sed -e "s/<.*//g;s/>.*//g;s/\x01/\./g;s/\"/'/g" \
                  | egrep "^_[^ ]* " | $CHARSETFILTER \
                  | perl -p -i -e "s/\&nbsp\;/ /g;s/\&/\&amp\;/g;s/</\&lt\;/g;s/>/\&gt\;/g" \
    		| while read SWITCH LINE
    		do
		    case "${SWITCH}" in

		    _TIME)

			PTIME=`echo ${LINE} | sed -e "s/\.//" | egrep "^[0-9][0-9][0-9][0-9]$"`
			if [ ! "${PTIME}" = "" ]
			then
			    [ 0${PTIME} -lt 0${PREPTIME} ] && ADDTIME="next day " 2> /dev/null
			    XMLTIME=`date -d "${ADDTIME}${CMDDATE}" +"%Y%m%d"`
			    PROGLINE="${ENDPROG}\t<programme channel='${CHANNELID}' start='${XMLTIME}${PTIME}00 ${TZONE}'>"
			    PREPTIME=0${PTIME}
			fi
			;;

		    _CATEGORY)
	    
			TYPE=`echo "${LINE}" | sed -e "s/'//;s/f\.gif/Film/;s/l\.gif/Zábava/;s/d\.gif/Dokument/;s/s\.gif/Seriál/;s/o\.gif/Sport/;s/q\.gif/Pro děti/;s/z\.gif/Zprávy/;s/m\.gif/Hudební pořad/"`
			CATEGORYLINE=""
			[ -n "${TYPE}" ] && CATEGORYLINE="\n\t\t<category>${TYPE}</category>"	    
			;;

		    _TITLE)

			if [[ "${RFC2838}" = "1" ]]
			then
			    TITLELINE=`convert_title "${LINE}" "\n\t\t"`
			else
			    TITLELINE="\n\t\t<title>${LINE}</title>\n\t\t<desc></desc>${CATEGORYLINE}"
			fi
			;;

		    _DESC)
	    
			TITLELINE=`echo "$TITLELINE" | sed -e "s|<desc></desc>|<desc>${LINE}</desc>|"`

			;;
		
		    _END)
		    
			[ -n "${PROGLINE}" ] && echo -e "${PROGLINE}${TITLELINE}\n\t</programme>" | sed -e "s|<desc></desc>||g" | egrep -v "^[[:space:]]*$" >> "${XMLDATA}"
			PROGLINE=""
			TITLELINE=""
			DESCLINE=""
			CATEGORYLINE=""
			;;
			    		
		    esac		
    		done	

#		VALTEMP=`tempfile`
#		cp ./xmltv.dtd /tmp/xmltv.dtd
#		echo "<?xml version=\"1.0\" encoding=\"${ENCODING}\"?>" >> ${VALTEMP}
#		echo "<!DOCTYPE tv SYSTEM \"xmltv.dtd\">" >> ${VALTEMP}
#		echo "<tv>" >> ${VALTEMP}
#		cat ${XMLDATA} >> ${VALTEMP}
#		echo "</tv>" >> ${VALTEMP}
#                echo "rxp -Vs -c ${ENCODING} ${VALTEMP}"
#		VALIDRES=`rxp -Vs -c ${ENCODING} ${VALTEMP} 2>&1`
#		if [ -n "${VALIDRES}" ] 
#		then
#		    cp ${XMLDATA} ${VALTEMP}
#		    echo -e "<!-- RXP Validation error: ${VALIDRES} -->\n<!-- " > ${XMLDATA}
#		    cat ${VALTEMP} >> ${XMLDATA}
#		    echo " -->" >> ${XMLDATA}
#		fi
#		rm ${VALTEMP}

	    fi	    
	fi

	[ -f "${XMLDATA}" ] && cat "${XMLDATA}"
	
    done

    let FORMDATE=FORMDATE+86400    

done

echo "</tv>"
