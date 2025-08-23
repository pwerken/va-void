
if [ -z "$1" ];
then
	echo "$0 <name>"
	exit 1
fi

DATE=$(date +"%Y%m%d%H%M%S")
FILE="${DATE}_$1.php"

cat << EOF > "$FILE"
<?php
declare(strict_types=1);

use App\\Migrations\\Migration;

class $1 extends Migration
{
    public function up(): void
    {
    }

    public function down(): void
    {
    }
}
EOF
