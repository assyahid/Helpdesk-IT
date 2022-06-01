<?php namespace App\Services\Files;

use App\Reply;
use App\Services\Mail\Parsing\ParsedEmail;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Storage;

class EmailStore
{
    /**
     * Store specified email on disk.
     *
     * @param ParsedEmail $parsedEmail
     * @param Reply $reply
     */
    public function storeEmail($parsedEmail, Reply $reply = null)
    {
        //if email was matched to existing ticket, we will use reply ID
        //as file name so we can later match it to corresponding reply
        if ($reply) {
            $path = $this->makeMatchedEmailPath($reply);

            //otherwise we will store email into "unmatched" directory
        } else {
            $path = $this->makeUnmatchedEmailPath();
        }

        Storage::put($path, $parsedEmail->toJson());
    }

    /**
     * Get original email for specified reply.
     */
    public function getEmailForReply(Reply $reply): ?array
    {
        $path = $this->makeMatchedEmailPath($reply);

        if (!Storage::exists($path)) {
            return null;
        }

        return json_decode(Storage::get($path), true);
    }

    /**
     * Make path for storing specified reply's email.
     *
     * @param Reply $reply
     * @return string
     */
    private function makeMatchedEmailPath(Reply $reply)
    {
        $date = $reply->created_at;
        return "emails/matched/{$date->year}/{$date->month}/{$date->day}/$reply->uuid.json";
    }

    /**
     * Make path for storing unmatched replies email.
     *
     * @return string
     */
    private function makeUnmatchedEmailPath()
    {
        $date = Carbon::now();
        $name = "{$date->hour}:{$date->minute}" . Str::random(30);
        return "emails/unmatched/{$date->year}/{$date->month}/{$date->day}/{$name}.json";
    }

    /**
     * Get all stored emails that were not matched to tickets.
     *
     * @return array
     */
    public function getUnmatchedEmails()
    {
        return Storage::allFiles('emails/unmatched');
    }
}
