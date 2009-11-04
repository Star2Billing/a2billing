find Public -name *__vt* > files2patch.txt
for i in `cat files2patch.txt`; do
echo $i | awk -F__vt '{print "diff -u "$1 $2" "$1"__vt"$2 " > diffs/"$1 $2".diff" }'
done
